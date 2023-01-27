<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function loadLogin(){
        return view('login');
    }

    public function userlogin(Request $request){
        $request->validate(
            [
                'email'=>'string|required|email',
                'password'=>'string|required'
            ]
            );

           $userCredential= $request->only('email','password');
          if(Auth::attempt($userCredential)){
            if (Auth::user()->is_admin == 1) {
                return redirect('/admin/dashboard');
            }
             else {
                return redirect('/dashboard');
            }
          }
          else{
            return back()->with('error','Username&Password is incorrect');
          }
           
           
    }

    public function dashboard(){
        return view('student.dashboard');
    }
    public function admin_dashboard()
    {
        return view('admin.admin_dashboard');
    }
    
    public function logout(Request $request){
        $request->session()->flush();
        Auth::logout();
        return redirect('/');
    }
}