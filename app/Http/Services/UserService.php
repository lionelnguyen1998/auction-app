<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Models\Contact;

class UserService implements UserServiceInterface
{
    public function __construct()
    {
        //Error Messages
        $this->messageRequired = config('message.MSG_01');
        $this->messageErrorFormatEmail = config('message.MSG_02');
        $this->messageErrorMax = config('message.MSG_03');
    }

    //register validation
    public function registerValidation($request) 
    {
        $rules = [
            'password' => 'required|max:255',
            're_pass' => 'required_with:password|same:password|max:255',
            'phone' => 'required|max:60',
            'address' => 'max:255',
            'name' => 'required|max:255'
        ];

        $allUserId = User::withTrashed()
            ->get()
            ->pluck('user_id')
            ->toArray();

        if (isset($request["user_id"])) {
            $userId = $request["user_id"];
            $rules['email'] = "required|max:255|unique:users,email,$userId,user_id,deleted_at,NULL";
        } else {
            foreach ($allUserId as $key => $id) {
                $rules['email'] = "required|max:255|unique:users,email,$id,user_id,deleted_at,NULL";
            }
        }

        $messages = [
            'required' => $this->messageRequired,
            'max' => sprintf($this->messageErrorMax, ':max'),
            'email' => $this->messageErrorFormatEmail,
            'same' => 'パスワードが違いました。',
            'required_with' => $this->messageRequired,
            'unique' => '既に使用されています。'
        ];

        $attribute = [
            'email' => 'メール',
            'password' => 'パスワード'
        ];

        $validated = Validator::make($request, $rules, $messages, $attribute);

        return $validated;
    }

    public function insertUser($request) 
    {
        $validated = $this->registerValidation($request);

        if ($validated->fails()) {
            return redirect(url()->previous())
                ->withErrors($validated)
                ->withInput();
        }

        $avatarDefault = "http://admin.localhost:443/storage/uploads/2022/03/12/avatar_Default.jpg";

        $user = User::create([
            'name' => $request['name'],
            'password' => Hash::make($request['password']),
            'email' => $request['email'], 
            'phone' => $request['phone'],
            'address' => $request['address'],
            'avatar' => $request['avatar'] ?? $avatarDefault,
            'role' => 2
        ]);

        return redirect()->route('loginUser');
    }

    public function updateUser($request)
    {
        $validated = $this->registerValidation($request);

        if ($validated->fails()) {
            return redirect(url()->previous())
                ->withErrors($validated)
                ->withInput();
        }

        $avatarDefault = "http://admin.localhost:443/storage/uploads/2022/03/12/avatar_Default.jpg";

        $user = User::findOrFail(auth()->user()->user_id);

        if ($user) {
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->phone = $request['phone'];
            $user->address = $request['address'] ?? null;
            $user->password = Hash::make($request['password']);
            $user->avatar = $request['avatar'] ?? $avatarDefault;
            return $user->update();
        } else {
            return false;
        }

    }

    //login validation
    public function loginValidation($request) 
    {
        $rules = [
            'email' => 'required|email:filter|max:255',
            'password' => 'required|max:255'
        ];

        $messages = [
            'required' => $this->messageRequired,
            'max' => sprintf($this->messageErrorMax, ':max'),
            'email' => $this->messageErrorFormatEmail
        ];

        $attribute = [
            'email' => 'メール',
            'password' => 'パスワード'
        ];

        $validated = Validator::make($request, $rules, $messages, $attribute);

        return $validated;
    }

    //sendEmail
    public function contactValidation($request) 
    {
        $rules = [
            'phone' => 'required|max:60',
            'name' => 'required|max:255',
            'email' => 'required|max:255|email'
        ];

        $messages = [
            'required' => $this->messageRequired,
            'max' => sprintf($this->messageErrorMax, ':max'),
            'email' => $this->messageErrorFormatEmail,
        ];

        $validated = Validator::make($request, $rules, $messages);

        return $validated;

    }

    public function sendEmail($request)
    {
        $validated = $this->contactValidation($request);

        if ($validated->fails()) {
            return redirect(url()->previous())
                ->withErrors($validated)
                ->withInput();
        }

        $newContact = Contact::create([
            'email' => $request['email'],
            'phone' => $request['phone'],
            'name' => $request['name'],
            'content' => $request['content']
        ]);

        $adminMail = config('mail.mailers.smtp.username');

        Mail::to($adminMail)->send(new SendMail($newContact));
    }


    //API
    public function register($request) 
    {
        $avatarDefault = "http://admin.localhost:443/storage/uploads/2022/03/12/avatar_Default.jpg";

        $user = User::create([
            'name' => $request['name'],
            'password' => Hash::make($request['password']),
            'email' => $request['email'], 
            'phone' => $request['phone'],
            'address' => $request['address'] ?? null,
            'avatar' => $request['avatar'] ?? $avatarDefault,
            'role' => 2
        ]);

        return $user;
    }
}
