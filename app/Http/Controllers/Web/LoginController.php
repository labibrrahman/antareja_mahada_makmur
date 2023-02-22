<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class LoginController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect('dashboard');
        }else{
            return view('pages.auth.login.index', ['title' => 'Login']);
        }
    }

    public function actionlogin(Request $request)
    {
        $data = [
            'username' => $request->input('username'),
            'password' => $request->input('password'),
        ];

        if (Auth::Attempt($data)) {

            $data_user = json_decode(Auth::user());
            Session::put('id', $data_user->id);
            Session::put('username', $data_user->username);
            Session::put('full_name', $data_user->full_name);
            Session::put('departement_id', $data_user->departement_id);
            return redirect('dashboard');
        }else{
            Session::flash('error', 'Username atau Password Salah');
            return redirect('/');
        }
    }

    public function register(Request $request)
    {
        return view('pages.auth.register.index');
    }

    public function actionlogout()
    {
        Session::flush();
        Auth::logout();
        return redirect('/');
    }
}