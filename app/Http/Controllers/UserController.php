<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | User Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling User related pages.
    |
    */
    
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
     * Show the getting started page.
     * 
     * This is meant to be presented after users sign up.
     *
     * @return \Illuminate\Http\Response
     */
    public function gettingStarted()
    {
        return view('users.getting-started');
    }
}
