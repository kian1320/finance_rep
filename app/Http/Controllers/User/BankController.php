<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bank;
use App\Models\Summary;
use Illuminate\Support\Facades\Auth;

class BankController extends Controller
{
    public function index()
    {
        // Retrieve the currently authenticated user
        $user = Auth::user();

        // Retrieve banks added by the current user
        $banks = $user->banks;

        // Fetch the latest summary data
        $latestSummary = Summary::where('created_by', $user->id)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();

        // Calculate the total amount from the bank table
        $totalBankAmount = Bank::where('created_by', $user->id)
            ->sum('amount');

        // Add the bank total to the aftexpenses from the latest month
        $totalAmountWithBank = $latestSummary ? $latestSummary->aftexpenses + $totalBankAmount : 0;

        return view('user.bank.index', compact(
            'latestSummary',
            'banks',
            'totalBankAmount',
            'totalAmountWithBank'
        ));
    }





    public function create()
    {
        return view('user.bank.create'); // Use the correct view file name with the "user" folder
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'bname' => 'required',
            'accnum' => 'required',
            'amount' => 'required|numeric',
        ]);

        // Get the currently authenticated user
        $user = Auth::user();

        // Create a new bank record and associate it with the user
        $bank = new Bank([
            'date' => $request->input('date'),
            'bname' => $request->input('bname'),
            'accnum' => $request->input('accnum'),
            'amount' => $request->input('amount'),
        ]);

        // Associate the bank record with the user
        $bank->created_by = $user->id;

        // Save the bank record
        $bank->save();

        return redirect('user/bank')->with('success', 'Record deleted successfully');
    }


    public function edit($id)
    {
        $bank = Bank::find($id);
        return view('user.bank.edit', compact('bank')); // Use the correct view file name with the "user" folder
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required',
            'bname' => 'required',
            'accnum' => 'required',
            'amount' => 'required|numeric',
        ]);

        $bank = Bank::find($id);
        $bank->update($request->all());

        return redirect('user/bank')->with('success', 'Record updated successfully');
    }

    public function destroy($id)
    {
        $bank = Bank::find($id);
        $bank->delete();

        return redirect('user/bank')->with('success', 'Record deleted successfully');
    }
}
