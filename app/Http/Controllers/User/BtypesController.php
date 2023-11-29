<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Auth;
use App\Models\Btypes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Rules\UniqueBtypeName;

class BtypesController extends Controller
{



    public function index()
    {
        $user = Auth::user();
        $btypes = btypes::where('created_by', $user->id)->get();
        return view('user.btypes.index', compact('btypes'));
    }

    public function create()
    {

        $btypes = btypes::all();
        return view('user.btypes.create', compact('btypes'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', new UniqueBtypeName(Auth::user()->id)],
        ]);

        $user = Auth::user();

        // If validation passes, create and save the new Btypes record
        $btypes = new Btypes();
        $btypes->name = $request->name;
        $btypes->created_by = $user->id;

        try {
            // Attempt to save the record
            $btypes->save();
        } catch (\Exception $e) {
            // Handle the exception for duplicate entry
            return redirect()->back()->withErrors(['name' => 'The budget name already exists in the database.'])->withInput();
        }

        // If successfully saved, redirect to another page
        return redirect('user/btypes')->with('message', 'Added');
    }




    public function edit($items_id)
    {
        $btypes = btypes::find($items_id);
        return view('user.btypes.edit', compact('btypes'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $btype = Btypes::findOrFail($id);
        $btype->name = $request->name;
        $btype->save();

        // Optionally, you can redirect to another page after successful update
        return redirect('user/btypes')->with('message', 'Type updated');
    }


    public function destroy($id)
    {
        $btype = Btypes::findOrFail($id);
        $btype->delete();

        // Optionally, you can redirect to another page after successful deletion
        return redirect('user/btypes')->with('message', 'Btype deleted successfully');
    }
}
