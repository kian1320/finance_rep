<?php

namespace App\Http\Controllers\User;

use App\Models\Lexpenses;
use App\Models\Expenses;
use App\Models\Types;
use App\Models\Stypes;
use App\Models\Summary;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\LexpensesFormRequest;
use Mockery\Matcher\Type;

class LexpensesController extends Controller
{

    public function index()
    {


        $user = auth()->user();

        // Summary
        $Lexpenses = Lexpenses::where('created_by', $user->id)
            ->orderBy('id', 'desc')
            ->get();



        return view('user.lexpenses.index', compact('Lexpenses'));
    }


    public function getStypes(Request $request)
    {
        $typeId = $request->input('type_id');
        $stypes = Stypes::where('types_id', $typeId)->get();

        return response()->json(['stypes' => $stypes]);
    }

    public function create()
    {
        $user = auth()->user();

        $types = Types::all();
        $stypes = Stypes::all();

        return view('user.lexpenses.create', compact('types', 'stypes'));
    }


    public function store(LexpensesFormRequest $request)
    {
        $user = auth()->user();

        $data = $request->validated();

        $lexpenses = new Lexpenses;
        $lexpenses->date_issued = $data['date_issued'];
        $lexpenses->voucher = $data['voucher'];
        $lexpenses->check = $data['check'];
        $lexpenses->encashment = $data['encashment'];
        $lexpenses->description = $data['description'];
        $lexpenses->type_id = $data['type_id'];
        $lexpenses->stype_id = $data['stype_id'];
        $lexpenses->amount = $data['amount'];
        //$lexpenses->others = $data['others']; // Add this line for the 'others' field
        $lexpenses->created_by = Auth::user()->id;
        $lexpenses->save();

        return redirect('user/lexpenses')->with('message', 'Expenses Added');
    }



    public function edit($lexpenses_id)
    {
        $lexpenses = Lexpenses::findOrFail($lexpenses_id);
        $types = Types::all();
        $stypes = Stypes::all();


        return view('user.lexpenses.edit', compact('lexpenses', 'types', 'stypes'));
    }


    public function update(LexpensesFormRequest $request, $lexpenses_id)
    {
        $data = $request->validated();

        $lexpenses = Lexpenses::find($lexpenses_id);
        $lexpenses->date_issued = $data['date_issued'];
        $lexpenses->voucher = $data['voucher'];
        $lexpenses->check = $data['check'];
        $lexpenses->encashment = $data['encashment'];
        $lexpenses->description = $data['description'];
        $lexpenses->type_id = $data['type_id'];
        $lexpenses->amount = $data['amount'];
        $lexpenses->created_by = Auth::user()->id;
        $lexpenses->update();

        return redirect('user/lexpenses')->with('message', 'Expenses updated');
    }


    public function destroy($Lexpenses_id)
    {
        $Lexpenses = Lexpenses::find($Lexpenses_id);
        if ($Lexpenses) {
            $Lexpenses->delete();
            return redirect('user/expenses')->with('message', 'Expenses Deleted');
        } else {
            return redirect('user/expenses')->with('message', 'no item Id Found');
        }
    }


    public function addToExpenses(Request $request, $lexpensesId)
    {
        try {
            // Find the Lexpense record by its ID
            $lexpense = Lexpenses::find($lexpensesId);

            // Check if the expense has already been added
            if ($lexpense->isAdded) {
                // Return a response indicating that it's already added or handle this case accordingly
                return response()->json(['message' => 'Expense already added']);
            }

            // Create a new Expense record based on the Lexpense data
            $expense = new Expenses;
            $expense->date_issued = $lexpense->date_issued;
            $expense->voucher = $lexpense->voucher;
            $expense->check = $lexpense->check;
            $expense->encashment = $lexpense->encashment;
            $expense->description = $lexpense->description;
            $expense->type_id = $lexpense->type_id;
            $expense->stype_id = $lexpense->stype_id;
            $expense->amount = $lexpense->amount;
            $expense->created_by = Auth::user()->id;
            $expense->late_encash = 1;


            // Find the corresponding summary based on the encashment month
            $encashmentMonth = date('n', strtotime($request->encashment)); // Use 'n' instead of 'm' for month without leading zero
            $encashmentYear = date('Y', strtotime($request->encashment));

            // Find the user's ID
            $userId = Auth::user()->id;

            // Find the summary for the current user and month
            $summaryCurrentMonth = Summary::where('created_by', $userId)
                ->where('month', $encashmentMonth)
                ->where('year', $encashmentYear)
                ->first();

            if ($summaryCurrentMonth) {
                // Create a new Expense record and associate it with the user's summary of the current month
                $expense->created_by = $userId;
                $expense->summary_id = $summaryCurrentMonth->id;

                // Set the stype_id from the request data
                $expense->stype_id = $lexpense->stype_id;

                $expense->save();

                // Deduct the expense amount from the user's current month's aftexpenses column
                $summaryCurrentMonth->aftexpenses -= $expense->amount;
                $summaryCurrentMonth->save();

                // Update the is_added status in lexpenses table
                $lexpense->is_added = 1;
                $lexpense->save();

                // Find the summary for the next month
                $nextMonth = $encashmentMonth + 1;
                $nextYear = $encashmentYear;
                if ($nextMonth > 12) {
                    $nextMonth = 1;
                    $nextYear++;
                }
                $summaryNextMonth = Summary::where('created_by', $userId)
                    ->where('month', $nextMonth)
                    ->where('year', $nextYear)
                    ->first();

                if ($summaryNextMonth) {
                    // Update the next month's totalsrt and aftexpenses
                    $summaryNextMonth->totalstr -= $expense->amount;
                    $summaryNextMonth->aftexpenses -= $expense->amount;
                    $summaryNextMonth->save();
                }

                $expense->save();

                // Return a response indicating success
                return response()->json(['message' => 'Expense added successfully']);
            } else {
                // Handle the case when the user's current month's summary does not exist
                // You can either throw an error, return a response, or handle it as needed
                return response()->json(['message' => 'No summary found for the given encashment month'], 400);
            }
        } catch (\Exception $e) {
            // Handle any exceptions or errors here
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500); // HTTP 500 for server error
        }

        // Assuming $lexpense->stype_id contains the name of the subtype
        $subtypeName = $lexpense->stype_id;

        // Look up the subtype by name
        $subtype = Stypes::where('name', $subtypeName)->first();

        if ($subtype) {
            // Set the stype_id to the id of the found subtype
            $expense->stype_id = $subtype->id;
        } else {
            // Handle the case where the subtype is not found (e.g., show an error message)
            return response()->json(['message' => 'Subtype not found'], 404);
        }
    }
}
