<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Slider;
use App\Http\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        return view('users.login', [
            'title' => 'ログイン',
        ]);
    }

    public function register()
    {
        return view('users.register', [
            'title' => '登録',
        ]);
    }

    public function insertUser(Request $request)
    {
        $this->userService->insertUser($request->all());
        
        return redirect()->route('loginUser');
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

    public function edit($userId)
    {
        return view('users.edit', [
            'title' => 'アカウント編集',
            'user' => User::findOrFail($userId),
            'logo' => Slider::logo(),
        ]);
    }

    public function update(Request $request) 
    {
        $this->userService->updateUser($request->all());

        return redirect()->route('home')->with('message', '編集しました');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('loginUser');
    }

    public function contact()
    {
        return view('contact', [
            'title' => 'お間に合わせる',
            'logo' => Slider::logo(),
        ]);
    }

    public function contactUs(Request $request)
    {
        $this->userService->sendEmail($request->all());

        return redirect()->route('contact')->with('message', 'メールを送りました。');
    }
}
