<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\DB;
use App\Models\Btypes;
use App\Models\Bstypes;
use App\Models\Budget;
use App\Models\Summary;
use App\Models\Bothers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\BudgetFormRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;

class BudgetController extends Controller
{


    public function index()
    {
        $user = auth()->user();
        $budgets = Budget::where('created_by', $user->id)->get();
        return view('user.budget.index', compact('budgets'));
    }




    public function getBstypes(Request $request)
    {
        $btypeId = $request->input('btype_id');
        $bstypes = Bstypes::where('btypes_id', $btypeId)->get();

        return response()->json(['bstypes' => $bstypes]);
    }




    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'month' => 'required',
            'year' => 'required',
            'type' => 'required',
            'btypes_id' => 'required',   // Update to match your form field name
            'bstypes_id' => 'required',  // Update to match your form field name
            'amount' => 'required|numeric',  // Update to match your form field name and add numeric validation
        ]);

        // Get the last month's summary for the current user
        $lastMonthSummary = Summary::where('created_by', auth()->id())
            ->orderBy('id', 'desc')
            ->first();

        // Calculate the previous month's total aftexpenses and beginning balance
        $previousAftExpenses = 0;
        $beginBal = 0;

        if ($lastMonthSummary) {
            $previousAftExpenses = $lastMonthSummary->totalstr;
            $previousAftExpenses = $lastMonthSummary->aftexpenses;
            $beginBal = $lastMonthSummary->aftexpenses;
        }

        // Handle the transition from December to January
        if ($request->month == 1 && $lastMonthSummary) {
            $lastDecemberSummary = Summary::where('created_by', auth()->id())
                ->where('month', 12)
                ->where('year', $request->year - 1) // Get the December summary from the previous year
                ->first();

            if ($lastDecemberSummary) {
                $previousAftExpenses = $lastDecemberSummary->totalstr;
                $previousAftExpenses = $lastDecemberSummary->aftexpenses;
                $beginBal = $lastDecemberSummary->aftexpenses;
            }
        }

        // Get the existing summary for the current user, month, and year
        $summary = Summary::where('created_by', auth()->id())
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->first();


        // If the summary does not exist, create a new one
        if (!$summary) {
            $summary = Summary::create([
                'month' => $request->month,
                'year' => $request->year,
                'type' => $request->type,
                'created_by' => auth()->id(),
                'totalstr' => $previousAftExpenses,
                'aftexpenses' => $previousAftExpenses, // Set previous total aftexpenses
                'beginbal' => $beginBal, // Set previous month's aft expenses as beginbal or 0 if new user
            ]);
        }

        $btypeId = $request->input('btypes_id');
        $bstypeId = $request->input('bstypes_id');
        $budgetAmount = $request->input('amount');
        $others = $request->input('others', null); // Assign null as a default value if 'others' is not present

        if (is_numeric($budgetAmount)) {
            // Create a new budget record
            // Create a new budget instance
            $newBudget = new Budget([
                'summary_id' => $summary->id,
                'btypes_id' => $btypeId,
                'bstypes_id' => $bstypeId,
                'amount' => $budgetAmount,
                'others' => $others, // Add the 'others' field
                'created_by' => auth()->id(),
                'month' => $request->month,
                'year' => $request->year,
            ]);

            // Save the budget instance to the database
            $newBudget->save();

            // Create a new bothers record and link it to the budget
            Bothers::create([
                'summary_id' => $summary->id,
                'budget_id' => $newBudget->id,
                'btypes_id' => $btypeId,
                'bstypes_id' => $bstypeId,
                'created_by' => auth()->id(),
                'others' => $others,
            ]);

            // Add the new budget amount to both aftexpenses and totalstr
            $summary->totalstr += $budgetAmount;
            $summary->aftexpenses += $budgetAmount;
        }

        // Get the existing summary for the current user, next month, and year
        $nextMonthSummary = Summary::where('created_by', auth()->id())
            ->where('month', $request->month + 1)  // Assuming months are in the range 1-12
            ->where('year', $request->year)
            ->first();

        // If the next month's summary exists, update its values
        if ($nextMonthSummary) {
            $nextMonthSummary->beginbal += $budgetAmount;
            $nextMonthSummary->totalstr += $budgetAmount;
            $nextMonthSummary->aftexpenses += $budgetAmount;
            $nextMonthSummary->save();
        }

        $summary->save();


        return redirect('user/summary')->with('message', 'Budget Added');
    }


    public function edit($id)
    {
        $budget = Budget::find($id); // Retrieve the budget to edit
        $btypes = Btypes::all(); // Retrieve all budget types
        $bstypes = Bstypes::all(); // Retrieve all budget subtypes

        return view('user.budget.edit', compact('budget', 'btypes', 'bstypes'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'month' => 'required',
            'year' => 'required',
            'btypes_id' => 'required',
            'bstypes_id' => 'required',
            'amount' => 'required|numeric',
            'others' => 'nullable|string',
        ]);

        // Find the budget record to update
        $budget = Budget::find($id);

        if (!$budget) {
            return redirect('user/budget')->with('error', 'Budget not found.');
        }

        // Store the previous amount and others of the budget for later calculation
        $previousAmount = $budget->amount;
        $previousOthers = $budget->others;

        // Update the budget record with the new data
        $budget->month = $request->input('month');
        $budget->year = $request->input('year');
        $budget->btypes_id = $request->input('btypes_id');
        $budget->bstypes_id = $request->input('bstypes_id');
        $budget->amount = $request->input('amount');
        $budget->others = $request->input('others');
        $budget->save();

        // Calculate the difference between the new and previous budget amounts
        $amountDifference = $budget->amount - $previousAmount;

        // Find the summary record associated with the updated budget
        $summary = Summary::where('month', $budget->month)
            ->where('year', $budget->year)
            ->where('created_by', auth()->id())
            ->first();

        if ($summary) {
            // Update the summary based on the budget change
            $summary->totalstr += $amountDifference;
            $summary->aftexpenses += $amountDifference;
            $summary->save();
        }

        // Redirect back to the edit form with a success message
        return redirect('user/budget')->with('success', 'Budget updated successfully');
    }



    public function destroy($id)
    {
        $budget = Budget::find($id);

        if (!$budget) {

            return redirect('user/budget')->with('error', 'Budget not found.');
        }

        // Store the budget amount for later calculation
        $budgetAmount = $budget->amount;

        // Find the associated summary
        $summary = Summary::where('month', $budget->month)
            ->where('year', $budget->year)
            ->where('created_by', auth()->id())
            ->first();

        if ($summary) {
            // Update the summary based on the budget deletion
            $summary->totalstr -= $budgetAmount;
            $summary->aftexpenses -= $budgetAmount;
            $summary->save();
        }

        // Delete the budget record
        $budget->delete();


        return redirect('user/budget')->with('success', 'Budget deleted successfully');
    }
}
