<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\hash;

class MemberController extends Controller {

//    public function __construct()
//    {
//        $this->middleware('guest', ['except' => 'update']);
//    }
//    public function __construct()
//    {
//        $this->middleware('guest', ['except' => '']);
//    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


//    public function __construct()
//    {
//        //this function forbids guests from accessing all of the functions below except index.
//        // However, the middleware('auth') automatically turn the page to login.
//        $this->middleware('auth')->except(['index']);
//    }

    public function index()
    {
//        $a = auth::user()->type;
//        dd($a);
        return view('index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
//        if(!Auth::check()){
//            return redirect()->home();
//        }
        $user = Auth::user();

//        var_dump($user);
        return view('edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // First of all, we need to validate the items, especially give a confirmed function on password form
        $this->validate(request(), [
            'name'         => 'required',
            'email'        => 'required',
            'new_password' => 'required|confirmed'
        ]);

        // With Laravel's intrinsic setting, password must be hashed before stored into database,
        // if not, something would go wrong when verifying with 'Auth::attempt' during login process. We, therefore,
        // have to use 'Hash::check" function to compare unencrypted user-key-in current password to the password
        // stored in the database. if the result doesn't match, return error message, and if it does, then proceed
        // further.

        if (!(Hash::check($request->current_password, Auth::user()->password)))
        {
            return back()->withErrors(['message' => 'Please check your current password again']);
        }
        // Once the verifying work is done, we have to update the current data with new one. Be aware that
        // the new password has to be hashed before storing to make sure there will be no problem on
        // next verifying process with Auth::attempt
//        $user = Auth::user();
//        $user->name = $request->name;
//        $user->email = $request->email;
//        $user->password = bcrypt($request->new_password);
//        $user->save();
//
//        return redirect()->home();
        // or
        Auth::user()->update([
            'name'=>request('name'),
            'email'=>request('email'),
            'password'=>bcrypt(request('new_password'))
        ]);
        return redirect()->home();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
