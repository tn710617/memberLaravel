<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class RegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'register']);
    }
    public function create()
    {
        return view('registration.create');
    }

    public function store(Request $request)
    {
        // validate the form
        $this->validate(request(),[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        // create and save the user
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt(request('password'))
        ]);
//        dd($user);



        // Sign them in
        auth()->login($user);

        // Redirect to the home page
        return redirect()->home();
    }

}
