<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Http\Services\UserService;
use App\Models\User;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChatController extends ApiController
{
    protected $userService;
    public function __construct(Request $request, UserService $userService)
    {
        $this->userService = $userService;
    }

    //get conv of user login
    public function index() {
        $userSendId = auth()->user()->user_id;

        $conversations = Chat::with('userSend', 'userReceive')
            ->where('user_send_id', $userSendId)
            ->orWhere('user_receive_id', $userSendId)
            ->get();

        $data = [
            'chat' => $conversations->map(function ($c) use ($userSendId) {
                if ($userSendId === $c->user_send_id) {
                    return [
                        'chat_id' => $c->chat_id,
                        'user_send_id' => $c->user_send_id,
                        'user_receive_id' => $c->user_receive_id,
                        'user_receive_info' => [
                            'user_id' => $c['userReceive']->user_id,
                            'name' => $c['userReceive']->name,
                            'avatar' => $c['userReceive']->avatar
                        ]
                    ];
                } else {
                    return [
                        'chat_id' => $c->chat_id,
                        'user_send_id' => $c->user_send_id,
                        'user_receive_id' => $c->user_receive_id,
                        'user_receive_info' => [
                            'user_id' => $c['userSend']->user_id,
                            'name' => $c['userSend']->name,
                            'avatar' => $c['userSend']->avatar
                        ]
                    ];
                }
            })
        ];

        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];
    }

    public function chatValidation($request) {
        $rules = [
            'user_receive_id' => 'required'
        ];
        
        $messages = [
            'required' => '必須項目が未入力です。',
        ];

        $validated = Validator::make($request, $rules, $messages);

        return $validated;
    }

    //create conv of user login
    public function conversation(Request $request) {
        $userSendId = auth()->user()->user_id;
        $userReceiveId = $request['user_receive_id'];
        $validator = $this->chatValidation($request->all());

        if ($validator->fails()) {
            $userReceive = $validator->errors()->first("user_receive_id");
            return [
                "code" => 1001,
                "message" => 'user_receive_id: ' . $userReceive,
                "data" => null,
            ];
        }

        $chatId = Chat::where('user_receive_id', $userSendId)
            ->orWhere('user_send_id', $userSendId)
            ->get()
            ->pluck('chat_id');

        $checkConversation = Chat::where('chat_id', $chatId)
            ->where('user_receive_id', $userReceiveId)
            ->orWhere('user_send_id', $userReceiveId)
            ->get()
            ->first();

        if ($checkConversation) {
            return [
                "code" => 1000,
                "message" => "OK",
                "data" => $checkConversation,
            ];
        } else {
            $chat = Chat::create([
                'user_send_id' => $userSendId,
                'user_receive_id' => $userReceiveId,
            ]);
    
            return [
                "code" => 1000,
                "message" => "OK",
                "data" => $chat,
            ];
        }
    }

    public function messageValidation($request) {
        $rules = [
            'content' => 'required',
            'chat_id' => 'required',
        ];
        
        $messages = [
            'required' => '必須項目が未入力です。',
        ];

        $validated = Validator::make($request, $rules, $messages);

        return $validated;
    }

    //create message of conversation
    public function createMessage(Request $request) {
        $userSendId = $request['user_send_id'];
        $content = $request['content'];
        $chatId = $request['chat_id'];
        $validator = $this->messageValidation($request->all());

        if ($validator->fails()) {
            $messageValidate = $validator->errors()->first("content");
            return [
                "code" => 1001,
                "message" => 'content: ' . $messageValidate,
                "data" => null,
            ];
        }
        $checkConversation = Chat::where('user_send_id', $userSendId)
            ->orWhere('user_receive_id', $userSendId)
            ->where('chat_id', $chatId)
            ->get()
            ->first();

        if (empty($checkConversation)) {
            return [
                "code" => 1006,
                "message" => "Khong co quyen",
                "data" => null,
            ];
        } else {
            $message = Message::create([
                'chat_id' => $chatId,
                'user_send_id' => $userSendId,
                'content' => $content
            ]);

            $userSendInfo = User::where('user_id', $userSendId)
                ->select('name', 'avatar', 'user_id')
                ->get()
                ->first();

            $data = [
                'user_send_id' => $message->user_send_id,
                'user_send_info' => $userSendInfo,
                'content' => $message->content,
                'created_at' => $message->created_at->format('Y-m-d H:i:s')
            ];

            return [
                "code" => 1000,
                "message" => "OK",
                "data" => $data,
            ];
        }
    }

    //get all message of conversation
    public function listMessages($chatId) {
        $allMessages = Message::where('chat_id', $chatId)
            ->get();

        $data = $allMessages->map(function($message) {
            $userSendId = $message->user_send_id;
            $userSendInfo = User::where('user_id', $userSendId)
                ->select('name', 'avatar', 'user_id')
                ->get()
                ->first();

            return [
                'user_send_id' => $message->user_send_id,
                'user_send_info' => $userSendInfo,
                'content' => $message->content,
                'created_at' => $message->created_at->format('Y-m-d H:i:s')
            ];
        });

        return [
            "code" => 1000,
            "message" => "OK",
            "data" => $data,
        ];
    }
}
