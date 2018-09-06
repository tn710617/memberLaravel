<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller {

    public function __construct()
    {
        // This middleware('guest') function make all of the functions in this class only accessible to
        // guest layer except for 'destroy' method.

        $this->middleware('guest', ['except' => 'destroy']);
    }

    public function create()
    {
        return view('sessions.create');
    }

    public function store()
    {
        // Attempt to authenticate the user.
        // If not, redirect back.
        if (!auth()->attempt(request(['email', 'password'])))
        {
            return back()->withErrors(['message' => 'Please check your credentials and try again']);
        }

        // if so, sign them in.
        return redirect()->home();
        // Redirect to home page.
    }

    public function destroy()
    {
        auth()->logout();
//        dd('destroy');

        return redirect()->home();
    }
}


