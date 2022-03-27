<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function create(Request $request)
    {
        Comment::create([
            'user_id' => $request->user_id,
            'auction_id' => $request->auction_id,
            'content' => $request->content
        ]);

        return redirect()->back();
    }
}
