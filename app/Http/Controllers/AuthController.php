<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Mail\passResetMail;
// use Mail;

// use Illuminate\Support\Facades\Mail as FacadesMail;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Mail as FacadesMail;

class AuthController extends Controller
{
    public function loadRegister()
    {
        return view('register');
    }
    public function studentRegister(Request $request)
    {
        $request->validate(
            [
                'name' => 'string|required|min:2',
                'email' => 'string|required|email|max:100|unique:users',
                'password' => 'string|required|min:6|confirmed',
            ]
        );

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->back()->with('success', 'register successfull');
    }

    public function loadLogin()
    {
        if (Auth::user() && Auth::user()->is_admin == 1) {
            return redirect('/admin/dashboard');
        } elseif (Auth::user() && Auth::user()->is_admin == 0) {
            return redirect('/dashboard');
        }
        return view('login');
    }

    public function userlogin(Request $request)
    {
        $request->validate(
            [
                'email' => 'string|required|email',
                'password' => 'string|required'
            ]
        );

        $userCredential = $request->only('email', 'password');
        if (Auth::attempt($userCredential)) {
            if (Auth::user()->is_admin == 1) {
                return redirect('/admin/dashboard');
            } else {
                return redirect('/dashboard');
            }
        } else {
            return back()->with('error', 'Username&Password is incorrect');
        }
    }

    public function dashboard()
    {
        return view('student.dashboard');
    }
    public function admin_dashboard()
    {
        return view('admin.dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return redirect('/');
    }

    public function forgetpassLoading()
    {
        return view('forget_password');
    }

    public function forgetpassword(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->get();
            if (count($user) > 0) {
                $domain = URL::to('/');
                $token = Str::random(40);

                $url = $domain . '/reset-password?token=' . $token;

                $data['url'] = $url;
                $data['email'] = $request->email;
                $data['title'] = 'Password Reset';
                $data['body'] = 'Plseas click to reset password';

                FacadesMail::send('frogetPasswordMail', ['data' => $data], function ($message) use ($data) {
                    $message->to($data['email'])->subject($data['title']);
                });
                $dateTime = Carbon::now()->format('Y-m-d H:i:s');
                PasswordReset::updateOrCreate(
                    [
                        'email' => $request->email
                    ],
                    [
                        'email' => $request->email,
                        'token' => $token,
                        'created_at' => $dateTime
                    ]
                );
                return back()->with('success', 'Email send for reset password');
            } else {
                return back()->with('error', 'Email not exists');
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function resetPasswordLoad(Request $request)
    {
        $resetData = PasswordReset::where('token', $request->token)->get();
        if (isset($request->token) && count($resetData) > 0) {
            $user = User::where('email', $resetData[0]['email'])->get();
            return view('resetPassword', compact('user'));
        } else {
            return view('404');
        }
    }
    public function resetPassword(Request $request)
    {
        $request->validate(
            [
                'password'  => 'required|string|min:6|confiremd'
            ]
        );

        $user = User::find($request->id);
        $user->password = Hash::make($request->password);
        $user->save();

        PasswordReset::where('email', $user->email)->delete();
        return "<h2>password has been reset successfully</h2>";
    }
}
