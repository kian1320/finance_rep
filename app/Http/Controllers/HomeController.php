<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (auth()->user()->role_as == '1') {
            // Redirect admin users to admin dashboard
            return redirect()->route('dashboard');
        } elseif (auth()->user()->role_as == '0') {
            // Redirect user users to user dashboard
            return redirect()->route('dashboard');
        } else {
            // Handle other cases if needed
        }
    }

    // public function home()
    // {
    //     return view('home');
    // }
}
