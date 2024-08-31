<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Hash;
use Validator;

use App\Models\User;

class AuthController extends Controller
{
    public function getLogin()
    {
        return view('Auth.login');
    }

    public function postLogin(Request $request)
    {
        $email      =   strtolower($request->email);
        $password   =   $request->password;

        $this->validate($request, [
            'email'     =>  'required|email',
            'password'  =>  'required',
        ],
        [
            'email.required'    =>  'Alamat Email tidak boleh kosong',
            'email.email'       =>  'Alamat Email tidak valid',
            'password.required' =>  'Password tidak boleh kosong',
        ]);

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            if(Auth::user()->is_active == false) {
                Auth::logout();
                return redirect()->route('login')->with(['error' => 'Akun Anda tidak aktif']);
            }

            return redirect()->route('home');
        } else {
            Auth::logout();
            return redirect()->route('login')->with(['error' => 'Alamat Email atau Password salah']);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with(['success' => 'Akun Anda berhasil keluar']);
    }
}
