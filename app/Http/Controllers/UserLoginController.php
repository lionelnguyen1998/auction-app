<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserLoginController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        return view('users.login', [
            'title' => 'Login',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->userService->loginValidation($request->all());

        if ($validated->fails()) {
            return redirect(url()->previous())
                ->withErrors($validated)
                ->withInput();
        }

        $email = $request['email'];
        $password = $request['password'];
        $remember = $request['remember'];

        if (Auth::attempt([
                'email' => $email,
                'password' => $password
            ], $remember)) {
            //save session 
            $request->session()->put('email', $email);
            $request->session()->put('password', $password);

            //check remember 
            if (isset($remember)) {
                setcookie('email', $email, time() + 600);
                setcookie('password', $password, time() + 600);
            }

            return redirect()->route('home'); 
        }

        Session::flash('error', 'メールとパスワードは違いました');
        return redirect()->back();
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('loginUser');
    }
}
