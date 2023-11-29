<?php

namespace App\Http\Controllers\User;

use App\Models\Expenses;
use App\Models\Types;
use App\Models\Stypes;
use App\Models\Summary;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\ExpensesFormRequest;
use Mockery\Matcher\Type;

class ExpensesController extends Controller
{

    public function index()
    {
        $user = auth()->user();

        // Summary
        $Expenses = Expenses::where('created_by', $user->id)
            ->orderBy('id', 'desc')
            ->with('type', 'stype')
            ->get();


        $total = $Expenses->sum('amount');


        return view('user.expenses.index', compact('total', 'Expenses'));
    }



    public function getStypes(Request $request)
    {
        $typeId = $request->input('type_id');
        $stypes = Stypes::where('types_id', $typeId)->get();

        return response()->json(['stypes' => $stypes]);
    }


    public function create()
    {
        // Summary
        $Expenses = Expenses::orderBy('id', 'desc')->get();
        $total = $Expenses->sum('amount');

        // Fetch all types and stypes
        $types = types::all();
        $stypes = stypes::all();

        return view('user.expenses.create', compact('types', 'stypes', 'total', 'Expenses'));
    }


    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'date_issued' => 'required',
            'voucher' => 'required',
            'check' => 'required',
            'encashment' => 'required',
            'description' => 'required',
            'type_id' => 'required',
            'stype_id' => 'required',
            'amount' => 'required|numeric',
            'others' => 'nullable', // Validate others field as nullable
        ]);

        // Find the user's ID
        $userId = Auth::user()->id;

        // Parse the encashment date to determine the affected months
        $encashmentDate = strtotime($request->encashment);
        $encashmentMonth = date('n', $encashmentDate);
        $encashmentYear = date('Y', $encashmentDate);

        // Find the summary for the current user and month
        $summaryCurrentMonth = Summary::where('created_by', $userId)
            ->where('month', $encashmentMonth)
            ->where('year', $encashmentYear)
            ->first();

        if ($summaryCurrentMonth) {
            // Create a new Expense record and associate it with the user's summary of the current month
            $expense = new Expenses();
            $expense->fill($validatedData);
            $expense->created_by = $userId;
            $expense->summary_id = $summaryCurrentMonth->id;

            // Set the stype_id from the request data
            $expense->stype_id = $request->input('stype_id');

            // Calculate the impact on current and future months
            $impactAmount = $expense->amount;

            // Deduct the expense amount from the user's current month's aftexpenses column
            $summaryCurrentMonth->aftexpenses -= $impactAmount;
            $summaryCurrentMonth->save();

            // Update future months
            $nextMonth = $encashmentMonth;
            $nextYear = $encashmentYear;

            while (true) {
                // Move to the next month
                $nextMonth++;
                if ($nextMonth > 12) {
                    $nextMonth = 1;
                    $nextYear++;
                }

                // Find the summary for the next month
                $summaryNextMonth = Summary::where('created_by', $userId)
                    ->where('month', $nextMonth)
                    ->where('year', $nextYear)
                    ->first();

                if (!$summaryNextMonth) {
                    break; // No more future months to update
                }

                // Update the next month's totalsrt and aftexpenses
                $summaryNextMonth->totalstr -= $impactAmount;
                $summaryNextMonth->aftexpenses -= $impactAmount;
                $summaryNextMonth->beginbal -= $impactAmount;
                $summaryNextMonth->save();
            }

            // Save the new expense record
            $expense->save();

            return redirect('user/expenses')->with('message', 'Expenses Added');
        } else {
            // Handle the case when the user's current month's summary does not exist
            // You can either throw an error, redirect, or handle it as needed
            // For example, you can return an error message
            return redirect('user/expenses')->with('error', 'No summary found for the given encashment month');
        }
    }








    public function edit($expenses_id)
    {
        $expense = Expenses::findOrFail($expenses_id);
        $types = Types::all();
        $stypes = Stypes::all(); // Add this line to fetch all stypes

        return view('user.expenses.edit', compact('expense', 'types', 'stypes')); // Include $stypes in the compact function
    }





    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'date_issued' => 'required',
            'voucher' => 'required',
            'check' => 'required',
            'encashment' => 'required',
            'description' => 'required',
            'type_id' => 'required',
            'stype_id' => 'required',
            'amount' => 'required|numeric',
        ]);

        // Find the expense record to update
        $expense = Expenses::find($id);

        if (!$expense) {
            return redirect()->route('expenses.index')->with('error', 'Expense not found.');
        }

        // Store the previous amount of the expense for later calculation
        $previousAmount = $expense->amount;

        // Update the expense record with the new data
        $expense->date_issued = $request->input('date_issued');
        $expense->voucher = $request->input('voucher');
        $expense->check = $request->input('check');
        $expense->encashment = $request->input('encashment');
        $expense->description = $request->input('description');
        $expense->type_id = $request->input('type_id');
        $expense->stype_id = $request->input('stype_id');
        $expense->amount = $request->input('amount');
        $expense->save();

        // Calculate the difference between the new and previous expense amounts
        $amountDifference = $expense->amount - $previousAmount;

        // Find or create the summary record associated with the updated expense
        $summary = Summary::firstOrCreate([
            'month' => date('n', strtotime($expense->encashment)),
            'year' => date('Y', strtotime($expense->encashment)),
            'created_by' => auth()->id(),
        ]);

        // Update the aftexpenses field of the summary record
        $summary->update([
            'aftexpenses' => $summary->aftexpenses + $amountDifference,
        ]);

        // Redirect back to the edit form with a success message
        return redirect('user/expenses')->with('message', 'Expenses Updated');
    }















    public function destroy($id)
    {
        // Find the expense record to delete
        $expense = Expenses::find($id);

        if (!$expense) {
            return redirect()->route('expenses.index')->with('error', 'Expense not found.');
        }

        // Store the amount of the expense for later calculation
        $amount = $expense->amount;

        // Find the summary record associated with the deleted expense
        $summary = Summary::where('month', $expense->month) // You need to adjust this based on your logic
            ->where('year', $expense->year) // You need to adjust this based on your logic
            ->where('created_by', auth()->id())
            ->first();

        if ($summary) {
            // Adjust the summary by subtracting the expense amount
            $summary->aftexpenses -= $amount;
            $summary->save();
        }

        // Delete the expense
        $expense->delete();

        // Redirect back to the index with a success message
        return redirect('user/expenses')->with('message', 'Expense deleted successfully');
    }
}
