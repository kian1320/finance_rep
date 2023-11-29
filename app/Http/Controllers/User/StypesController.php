<?php

namespace App\Http\Controllers\User;

use App\Models\Types;
use App\Models\Stypes;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StypesController extends Controller
{
    public function index($types_id)
    {
        $types = Types::findOrFail($types_id);
        $subtypes = $types->stypes;

        return view('user.stypes.index', ['types_id' => $types_id, 'subtypes' => $subtypes, 'types' => $types]);
    }



    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'types_id' => 'required',
            'sname' => 'required',
        ]);

        $stypes = new Stypes;
        $stypes->types_id = $validatedData['types_id'];
        $stypes->sname = $validatedData['sname'];
        $stypes->added_by = Auth::user()->id;
        $stypes->save();

        return redirect('user/stypes/' . $stypes->types_id)->with('message', 'Subtype Added');
    }

    public function edit($id)
    {
        $stype = Stypes::findOrFail($id);
        return view('user.stypes.edit', ['stype' => $stype]);
    }



    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'sname' => 'required',
        ]);

        $stype = Stypes::findOrFail($id);
        $stype->sname = $validatedData['sname'];
        $stype->save();


        return redirect('user/stypes/' . $stype->types_id)->with('message', 'Subtype Edited');
    }





    public function destroy($types_id, $subtype_id)
    {
        $subtype = Stypes::findOrFail($subtype_id);
        $subtype->delete();

        return redirect('user/stypes/' . $types_id)->with('message', 'Subtype Deleted');
    }
}
