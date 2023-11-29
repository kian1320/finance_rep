<?php

namespace App\Http\Controllers\User;

use App\Models\Btypes;
use App\Models\Bstypes;
use App\Models\Summary;
use App\Models\Ysummary;
use App\Models\Expenses;
use App\Models\Types;
use App\Models\Budget;
use App\Models\Ybudgets;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class YsummaryController extends Controller
{
    public function index(Request $request)
    {

        $user = auth()->user();

        // Get the current year
        $currentYear = date('Y');

        $selectedYear = $request->input('year', $currentYear);



        // Fetch all unique years from the summary table
        $availableYears = Summary::select('year')
            ->where('created_by', $user->id)
            ->groupBy('year')
            ->pluck('year')
            ->toArray();

        $Expenses = Expenses::where('created_by', $user->id)
            ->whereYear('encashment', $selectedYear) // Use selected year instead of $currentYear
            ->orderBy('id', 'desc')
            ->with('type', 'stype')
            ->get();


        $total = $Expenses->sum('amount');

        // Get the user's expense types
        $userExpenseTypes = Types::where('created_by', $user->id)->pluck('id');

        // Fetch the user's expense types
        $types = Types::whereIn('id', $userExpenseTypes)->get();

        // Get the user's budget types and subtypes
        $btypes = Btypes::where('created_by', $user->id)->get();
        $bstypes = Bstypes::where('added_by', $user->id)->get();
        $ybudgetsData = Ybudgets::all();

        // Get the user's yearly summary data for the current year
        $summary = Summary::where('created_by', $user->id)
            ->where('year', $selectedYear)
            ->get();



        // Initialize arrays to store yearly summaries and months
        $yearlySummaries = [];
        $months = [];

        // Loop through the retrieved months and populate the $months array
        foreach ($summary as $item) {
            $month = $item->month;

            // Populate the months array with the current and previous month
            if (!in_array($month, $months)) {
                $months[] = $month;
            }
        }

        // Loop through each unique month in reverse order
        foreach ($months as $month) {
            // Calculate the year for the month
            $yearForMonth = ($month == 1) ? $currentYear - 1 : $currentYear;

            // Retrieve summaries for the specific year and month
            $monthlySummary = Summary::where('created_by', $user->id)
                ->whereYear('created_at', $yearForMonth)
                ->whereMonth('created_at', $month)
                ->get();
            // Store the calculated monthly summary in the yearlySummaries array
            $yearlySummaries[$yearForMonth][$month] = $monthlySummary;
        }


        // Calculate the total budget amount for the current year
        $totalBudgetAmount = DB::table('budgets')
            ->whereYear('created_at', $currentYear)
            ->sum('amount');

        // Calculate other totals or perform additional calculations
        $totalOtherCalculations = // Perform your calculations here

            //YEARLY SUMMARY TOTAL FOR EACH BUDGET TYPES
            // Calculate the current year
            $currentYear = Carbon::now()->year;

        // Retrieve all unique years from the summary table
        $yearData = Summary::select('year')->groupBy('year')->get();

        $budgetDataByYear = []; // Initialize the $budgetDataByYear array

        foreach ($yearData as $yearItem) {
            $year = $yearItem->year;

            // Retrieve budget data for the entire year
            $summaryIds = Summary::where('year', $year)->pluck('id')->toArray();

            $budgetData = []; // Initialize the $budgetData array

            foreach ($summaryIds as $summaryId) {
                $currentYearBudgets = Budget::where('summary_id', $summaryId)->get();

                foreach ($currentYearBudgets as $budget) {
                    $btypeName = $budget->btype->name;
                    $bsname = $budget->bstype->bsname;
                    $amount = $budget->amount;

                    // Organize budget data
                    if (!isset($budgetData[$btypeName])) {
                        $budgetData[$btypeName] = [];
                    }

                    if (!isset($budgetData[$btypeName][$bsname])) {
                        $budgetData[$btypeName][$bsname] = 0;
                    }

                    $budgetData[$btypeName][$bsname] += $amount;
                }
            }

            $budgetDataByYear[$year] = $budgetData;
        }



        //actual
        // Fetch data from the database and calculate total amounts
        $incomeData = Ybudgets::select('bstypes_id', \DB::raw('SUM(amount) as total_amount'))
            ->groupBy('bstypes_id')
            ->get();

        // Fetch the names of bstypes from another table (assuming you have a 'bstypes' table)
        $bstypes = Bstypes::all()->pluck('name', 'id');

        // Create an associative array to store the data
        $incomeCategories = [];

        foreach ($incomeData as $row) {
            $bstypeId = $row->bstypes_id;
            $bstypeName = $bstypes->get($bstypeId); // Get the bstype name from the plucked data

            // Add data to the associative array
            $incomeCategories[$bstypeName][] = [
                'amount' => number_format($row->total_amount, 2),
            ];
        }






        $months = Expenses::where('created_by', $user->id)

            ->select(DB::raw('MONTH(encashment) as month'))
            ->groupBy('month')
            ->orderByDesc('month') // Order by month in descending order
            ->pluck('month')
            ->sort()
            ->values()
            ->all();




        // Retrieve the expenses for each month
        $totalExpensesByType = [];

        foreach ($months as $month) {
            $expenses = Expenses::where('created_by', $user->id)
                ->whereMonth('encashment', $month)
                ->whereIn('type_id', $userExpenseTypes)
                ->select('type_id', DB::raw('SUM(amount) as total_amount'))
                ->groupBy('type_id')
                ->get();

            $totalExpensesByType[$month] = $expenses;
        }
        $totalExpensesByStypeAndType = [];

        foreach ($types as $type) {
            foreach ($type->stypes as $stype) {
                $monthlyExpenses = []; // Initialize an array to store monthly expenses

                foreach ($months as $month) {
                    $expenses = Expenses::where('created_by', $user->id)
                        ->whereMonth('encashment', $month)
                        ->where('type_id', $type->id)
                        ->where('stype_id', $stype->id)
                        ->sum('amount');

                    $monthlyExpenses[$month] = $expenses; // Store monthly expenses for this month
                }

                // Store the monthly expenses for this combination of $stype and $type
                $totalExpensesByStypeAndType[$stype->id][$type->id] = $monthlyExpenses;
            }
        }



        // Total expenses per year
        $currentYear = Carbon::now()->year;
        $years = [$currentYear];
        $expensesByYear = Expenses::where('created_by', $user->id)
            ->whereIn('type_id', $userExpenseTypes) // Filter expenses by user's types
            ->selectRaw('YEAR(encashment) as year, SUM(amount) as total_amount')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        $totalExpensesByYear = [];

        // Loop through the expenses by year and populate the total expenses array
        foreach ($expensesByYear as $expense) {
            $year = $expense->year;
            $totalAmount = $expense->total_amount;

            $totalExpensesByYear[$year] = $totalAmount;
        }

        // Calculate total ending balance sum
        $currentYear = date('Y');

        // Get total budget amount for the current year
        $totalBudgetAmount = Budget::whereYear('created_at', $currentYear)
            ->where('created_by', $user->id)
            ->sum('amount');


        // Calculate total expenses for the authenticated user for the current year
        $totalExpenses = Expenses::whereYear('encashment', $currentYear)
            ->where('created_by', $user->id)
            ->sum('amount');


        // Calculate the remaining budget after deducting total expenses
        $remainingBudget = $totalBudgetAmount - $totalExpenses;

        return view('user.ysummary.index', compact(
            'summary',
            'months',
            'incomeCategories',
            'ybudgetsData',
            'years',
            'types',
            'totalBudgetAmount',
            'total',
            'totalExpensesByType',
            'user',
            'btypes',
            'bstypes',
            'budgetDataByYear',
            'currentYear',
            'totalExpensesByStypeAndType',
            'totalBudgetAmount',
            'totalExpenses',
            'remainingBudget',
            'availableYears',
            'selectedYear'

        ));
    }

    public function yindex()
    {
        $btypes = Btypes::all();
        $bstypes = Bstypes::all();


        $years = [2023, 2022, 2021]; // Replace with your list of years

        $budgetDataByYear = []; // Initialize an array to store budget data

        // Loop through each year
        foreach ($years as $year) {
            // Initialize an array for the current year
            $budgetDataByYear[$year] = [];

            // Loop through each btype and bstype
            foreach ($btypes as $btype) {
                foreach ($bstypes as $bstype) {
                    // Retrieve the budget data for the current btype and bstype for the given year
                    $budget = Ybudgets::where('year', $year)
                        ->where('btypes_id', $btype->id)
                        ->where('bstypes_id', $bstype->id)
                        ->sum('amount');

                    // Store the budget data in the array
                    $budgetDataByYear[$year][$btype->name][$bstype->bsname] = $budget;
                }
            }
        }

        return view('user.ysummary.index', compact('btypes', 'bstypes', 'years', 'budgetDataByYear'));
    }


    public function create()
    {
        $user = Auth::user();
        $btypes = btypes::where('created_by', $user->id)->get();
        return view('user.ysummary.create', compact('btypes'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'year' => 'required',
            'type' => 'required',
            'budgets' => 'required|array',
        ]);

        // Create a new Ysummary record
        $ysummary = Ysummary::create([
            'year' => $request->year,

            'type' => $request->type,
            'created_by' => auth()->id(),
            'beginbal' => 0, // You can adjust this if needed
            'totalstr' => 0, // You can adjust this if needed
            'aftexpenses' => 0, // You can adjust this if needed
        ]);

        // Process the budget data
        $budgetData = $request->input('budgets');

        foreach ($budgetData as $btypeId => $bstypes) {
            foreach ($bstypes as $bstypeId => $budgetAmount) {
                if (is_numeric($budgetAmount)) {
                    Ybudgets::create([
                        'ysummary_id' => $ysummary->id,
                        'btypes_id' => $btypeId,
                        'bstypes_id' => $bstypeId,
                        'amount' => $budgetAmount,
                        'created_by' => auth()->id(),
                    ]);
                }
            }
        }

        // Calculate the total budget
        $totalBudget = array_sum(array_map('array_sum', $budgetData));

        // Update the Ysummary record with the total budget
        $ysummary->totalstr = $totalBudget + $ysummary->beginbal;
        $ysummary->aftexpenses = $totalBudget + $ysummary->beginbal;
        $ysummary->save();

        return redirect('user/ysummary')->with('message', 'Yearly Budget Added');
    }
}
