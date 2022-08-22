<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            "user_id" => "required",
            "post_id" => "required",
            "coment" => "required|min:1"
        ]);
        if(auth()->user()->id === $request->user_id){
            $data = Comment::create([
                "user_id" => $request->user_id,
                "post_id" => $request->post_id,
                "coment" => $request->coment
            ]);
            return response()->json([
                "success" => true,
                "message" => "Comented!"
            ]);
        }
        return response()->json([
            "success" => false,
            "message" => "Invalid action"
        ]);
    }
}
