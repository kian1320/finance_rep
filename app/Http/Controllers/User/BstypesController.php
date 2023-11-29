<?php

namespace App\Http\Controllers\User;

use App\Models\Btypes;
use App\Models\Bstypes;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BstypesController extends Controller
{
    public function index($btypes_id)
    {
        $btypes = Btypes::findOrFail($btypes_id);
        $bsubtypes = $btypes->bsubtypes;

        return view('user.bstypes.index', ['btypes_id' => $btypes_id, 'bsubtypes' => $bsubtypes, 'btypes' => $btypes]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'btypes_id' => 'required',
            'sname' => 'required',
        ]);

        $bstype = new Bstypes;
        $bstype->btypes_id = $validatedData['btypes_id'];
        $bstype->bsname = $validatedData['sname'];
        $bstype->added_by = Auth::user()->id;
        $bstype->save();

        return redirect('user/bstypes/' . $bstype->btypes_id)->with('message', 'Subtype Added');
    }


    public function edit($id)
    {
        $bstype = Bstypes::findOrFail($id);
        return view('user.bstypes.edit', ['bstype' => $bstype]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'bsname' => 'required',
        ]);

        $bstype = Bstypes::findOrFail($id);
        $bstype->bsname = $validatedData['bsname'];
        $bstype->save();

        return redirect('user/bstypes/' . $bstype->btypes_id)->with('message', 'Subtype Edited');
    }



    public function destroy($btypes_id, $subtype_id)
    {
        $subtype = Bstypes::findOrFail($subtype_id);
        $subtype->delete();

        return redirect('user/bstypes/' . $btypes_id)->with('message', 'Subtype Deleted');
    }
}
