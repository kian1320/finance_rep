<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;

class ChangePasswordController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showChangeForm()
    {
        return view('user.chngpass.index');
    }

    public function changePassword(Request $request)
    {

        //dd($request->all());

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            dd($validator->errors()); // Check for validation errors
            return redirect()->back()->withErrors($validator);
        }

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            // dd('Password mismatch'); // Check if the passwords match
            return redirect()->back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }


        $user->password = Hash::make($request->password);
        $user->save();

        //return redirect()->route('home')->with('success', 'Password changed successfully!');
        return redirect()->back()->with('success', 'Password changed successfully!');
    }
}
