<?php

namespace App\Http\Controllers\User;

use App\Models\Btypes;
use App\Models\Bstypes;
use App\Models\Summary;
use App\Models\Expenses;
use App\Models\Lexpenses;
use App\Models\Types;
use App\Models\Stypes;
use App\Models\Budget;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SummaryController extends Controller
{



    public function submitSummary($id)
    {
        $summary = Summary::find($id);

        if (!$summary) {
            return response()->json(['success' => false, 'message' => 'Summary not found'], 404);
        }

        $action = request('action');

        if ($action === 'submit') {
            // User submission
            $summary->submitted = 1;
            $message = 'Summary submitted successfully';
        } elseif ($action === 'unsubmit') {
            // User unsubmission
            $summary->submitted = 0;
            $message = 'Summary unsubmitted successfully';
        }

        $summary->save();

        return response()->json(['success' => true, 'message' => $message, 'submitted' => $summary->submitted]);
    }








    public function index(Request $request)
    {
        $user = auth()->user();


        // Get the expenses types created by the user
        $userExpenseTypes = Types::where('created_by', $user->id)->pluck('id');


        // Fetch the expense types
        // Fetch all types (not limited to the current user)
        $types = Types::all();

        // Fetch all stypes (not limited to the current user)
        $stypes = Stypes::all();


        $btypes = Btypes::all();
        $bstypes = Bstypes::all();


        $availableYears = Summary::select(DB::raw('YEAR as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();


        $selectedYear = $request->input('year', null);
        // Define the Summary query
        $summaryQuery = Summary::where('created_by', $user->id);

        // Filter summary by year if a year is selected
        if ($selectedYear) {
            $summaryQuery->where('year', $selectedYear);
        }

        // Fetch the summary data
        $summary = $summaryQuery
            ->orderBy('month', 'desc')
            ->get()
            ->reverse();


        $currentMonthSummary = Summary::where('created_by', $user->id)
            ->orderBy('id', 'desc')
            ->first();


        // Fetch budget data for each month
        $budgetMonths = Budget::select('year', 'month')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'asc')
            ->get();


        // Initialize an array to store the total budget amounts by month, btype, and bstype
        $totalBudgetAmountByMonth = [];

        foreach ($budgetMonths as $budgetMonth) {
            $year = $budgetMonth->year;
            $month = $budgetMonth->month;

            // Initialize the array for the current month
            if (!isset($totalBudgetAmountByMonth[$month])) {
                $totalBudgetAmountByMonth[$month] = [];
            }

            // Loop through each btype
            foreach ($btypes as $btype) {
                // Initialize the array for the current btype
                if (!isset($totalBudgetAmountByMonth[$month][$btype->name])) {
                    $totalBudgetAmountByMonth[$month][$btype->name] = [];
                }

                // Loop through each bstype
                foreach ($btype->bstypes as $bstype) {
                    // Retrieve all budget records for the current month, btype, and bstype
                    $budgets = Budget::where('created_by', $user->id)
                        ->where('year', $year)
                        ->where('month', $month)
                        ->where('btypes_id', $btype->id)
                        ->where('bstypes_id', $bstype->id)
                        ->get();

                    // Sum the budget amounts for the same bstype
                    $totalBudgetAmount = $budgets->sum('amount');

                    // Store the total budget amount in the array
                    $totalBudgetAmountByMonth[$month][$btype->name][$bstype->bsname] = $totalBudgetAmount;
                }
            }
        }



        //dd($totalBudgetAmountByMonth);



        // Late expenses (apply year filter here)
        $monthsLateEncash1 = Expenses::where('created_by', $user->id)
            ->where('late_encash', 1)
            ->when($selectedYear, function ($query) use ($selectedYear) {
                return $query->whereYear('encashment', $selectedYear);
            })
            ->select(DB::raw('MONTH(encashment) as month'))
            ->groupBy('month')
            ->orderByDesc('month')
            ->pluck('month')
            ->sort()
            ->values()
            ->all();

        // Retrieve the expenses for each month where late_encash is equal to 1
        $totalExpensesByType1 = [];
        $totalExpensesByStypeAndType1 = [];

        foreach ($monthsLateEncash1 as $month) {
            $expenses = Expenses::where('created_by', $user->id)
                ->whereMonth('encashment', $month)
                ->whereIn('type_id', $userExpenseTypes)
                ->select('type_id', DB::raw('SUM(amount) as total_amount'))
                ->groupBy('type_id')
                ->get();

            $totalExpensesByType1[$month] = $expenses;
        }

        foreach ($monthsLateEncash1 as $month) {
            foreach ($types as $type) {
                foreach ($type->stypes as $stype) {
                    $expenses = Expenses::where('created_by', $user->id)
                        ->whereMonth('encashment', $month)
                        ->where('type_id', $type->id)
                        ->where('stype_id', $stype->id)
                        ->where('late_encash', 1)
                        ->select(DB::raw('SUM(amount) as total_amount'))
                        ->sum('amount');

                    $totalExpensesByStypeAndType1[$stype->id][$type->id][$month] = $expenses;
                }
            }
        }





        // Regular expenses (apply year filter here)
        $months = Expenses::where('created_by', $user->id)
            ->where('late_encash', '<>', 1)
            ->when($selectedYear, function ($query) use ($selectedYear) {
                return $query->whereYear('encashment', $selectedYear);
            })
            ->select(DB::raw('MONTH(encashment) as month'))
            ->groupBy('month')
            ->orderByDesc('month')
            ->pluck('month')
            ->sort()
            ->values()
            ->all();




        // Retrieve the expenses for each month
        $totalExpensesByType = [];
        $totalExpensesByStypeAndType = [];

        foreach ($months as $month) {
            $expenses = Expenses::where('created_by', $user->id)
                ->whereMonth('encashment', $month)
                ->whereIn('type_id', $userExpenseTypes)
                ->select('type_id', DB::raw('SUM(CASE WHEN late_encash = 1 THEN 0 ELSE amount END) as total_amount'))
                ->groupBy('type_id')
                ->get();

            $totalExpensesByType[$month] = $expenses;
        }

        foreach ($months as $month) {
            foreach ($types as $type) {
                foreach ($type->stypes as $stype) {
                    $expenses = Expenses::where('created_by', $user->id)
                        ->whereMonth('encashment', $month)
                        ->where('type_id', $type->id)
                        ->where('stype_id', $stype->id)
                        ->where('late_encash', '<>', 1) // Exclude expenses with late_encash = 1
                        ->select(DB::raw('SUM(amount) as total_amount'))
                        ->sum('amount');

                    $totalExpensesByStypeAndType[$stype->id][$type->id][$month] = $expenses;
                }
            }
        }








        // Total expenses per month
        $expensesByMonth = Expenses::where('created_by', $user->id)

            ->selectRaw('YEAR(encashment) as year, MONTH(encashment) as month, SUM(amount) as total_amount')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()

            ->reverse();

        $totalExpensesByMonth = [];

        // Loop through the expenses by month and populate the total expenses array
        foreach ($expensesByMonth as $expense) {
            $year = $expense->year;
            $month = $expense->month;
            $totalAmount = $expense->total_amount;

            $totalExpensesByMonth[] = [
                'year' => $year,
                'month' => $month,
                'total_amount' => $totalAmount,
            ];
        }

        $lexpensesWithIsAdded0 = Lexpenses::where('is_added', 0)
            ->where('created_by', auth()->user()->id) // Filter by the currently logged-in user
            ->get();
        $totalAmount = $lexpensesWithIsAdded0->sum('amount');



        return view('user.summary.index', compact(
            'summary',
            'months',
            'types',
            'stypes',
            'totalExpensesByMonth',
            'totalExpensesByType',
            'totalExpensesByStypeAndType1',
            'monthsLateEncash1',
            'user',
            'btypes',
            'bstypes',
            'totalExpensesByStypeAndType',
            'totalBudgetAmountByMonth', // Pass the total budget amount by month to the view
            'lexpensesWithIsAdded0',
            'totalAmount',
            'selectedYear',
            'availableYears'
        ));
    }







    public function create()
    {
        // $user = Auth::user();
        // $btypes = btypes::where('created_by', $user->id)->get();

        $btypes = Btypes::all();
        $bstypes = Bstypes::all();
        return view('user.summary.create', compact('btypes', 'bstypes'));
    }



    public function edit()
    {
        $user = Auth::user();
        $btypes = btypes::where('created_by', $user->id)->get();
        return view('user.addbudget.edit', compact('btypes'));
    }


    //additional budget 
    public function make()
    {
        $user = Auth::user();
        $btypes = btypes::where('created_by', $user->id)->get();
        return view('user.summary.addbudget', compact('btypes'));
    }
}
