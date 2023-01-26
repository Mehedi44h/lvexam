<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loadRegister(){
        return view('register');
    }
    public function studentRegister(Request $request){
        $request->validate(
            [
                'name'=>'string|required|min:2',
                'email'=>'string|required|email|max:100|unique:users',
                'password' => 'string|required|min:6|confirmed',
            ]
            );

            $user=new User;
            $user->name=$request->name;
            $user->email=$request->email;
            $user->password=Hash::make($request->password);
            $user->save();
            return redirect()->back()->with('success','register successfull');
            
    }
}