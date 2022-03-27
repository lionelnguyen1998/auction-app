<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\UploadService;

class UserService implements UserServiceInterface
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        //Error Messages
        $this->messageRequired = config('message.MSG_01');
        $this->messageErrorFormatEmail = config('message.MSG_02');
        $this->messageErrorMax = config('message.MSG_03');
        $this->uploadService = $uploadService;
    }

    //register validation
    //api
    public function registerValidation($request) 
    {
        $rules = [
            'password' => 'required|max:255',
            're_pass' => 'required_with:password|same:password|max:255',
            'phone' => 'required|max:60',
            'address' => 'max:255',
            'name' => 'required|max:255'
        ];

        $allUserEmail = User::withTrashed()
            ->get()
            ->pluck('email')
            ->toArray();

        if (isset($request["user_id"])) {
            $userId = $request["user_id"];
            $rules['email'] = "required|email|max:255|unique:users,email,$userId,user_id,deleted_at,NULL";
        } else {
            foreach ($allUserEmail as $key => $value) {
                if ($request['email'] == $value) {
                    $rules['email'] = "required|email|max:255|unique:users,email";
                } else {
                    $rules['email'] = "required|email|max:255";
                }
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
    //api
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
    //api
    public function contactValidation($request) 
    {
        $rules = [
            'phone' => 'required|max:60',
            'name' => 'required|max:255',
            'email' => 'required|max:255|email',
            'content' => 'required'
        ];

        $messages = [
            'required' => $this->messageRequired,
            'max' => sprintf($this->messageErrorMax, ':max'),
            'email' => $this->messageErrorFormatEmail,
        ];

        $validated = Validator::make($request, $rules, $messages);

        return $validated;

    }

    //api
    public function sendEmail($request)
    {
        $newContact = Contact::create([
            'email' => $request['email'],
            'phone' => $request['phone'],
            'name' => $request['name'],
            'content' => $request['content']
        ]);

        $adminMail = config('mail.mailers.smtp.username');

        Mail::to($adminMail)->send(new SendMail($newContact));

        return $newContact;
    }


    //API
    public function register($request) 
    {
        $avatarDefault = "http://admin.localhost:443/storage/uploads/2022/03/12/avatar_Default.jpg";

        if (isset($request['avatar'])) {
            $request['avatar'] = $this->uploadService->store($request['avatar']);
        }

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

    public function edit($request)
    {
        $avatarDefault = "http://admin.localhost:443/storage/uploads/2022/03/12/avatar_Default.jpg";

        if (isset($request['avatar'])) {
            $request['avatar'] = $this->uploadService->store($request['avatar']);
        }

        $request['password'] = Hash::make($request['password']);

        $user = tap(User::where('user_id', auth()->user()->user_id))
            ->update($request)->firstOrFail();

        auth()->user()->name = $user->name;
        auth()->user()->email = $user->email;
        auth()->user()->phone = $user->phone;
        auth()->user()->address = $user->address ?? null;
        auth()->user()->password = Hash::make($user->password);
        auth()->user()->avatar = $user->avatar ?? $avatarDefault;

        $message = "update thanh cong";
        return $message;
    }
}
