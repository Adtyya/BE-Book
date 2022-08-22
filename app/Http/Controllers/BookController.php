<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\User;
use App\Http\Resources\BookResource;

class BookController extends Controller
{
    public function __construct(){
        $this->middleware("auth:api");
    }

    public function show()
    {
        $books = Book::with("users")->orderBy("created_at", "DESC");
        return BookResource::collection($books->paginate(8))->response(); 
    }

    public function edit(Request $request)
    {
        $request->validate([
            "book_id" => "required",
            "title" => "required",
            "description" => "required|min:8"
        ]);
        try {
            $id = $request->book_id;
            $book = Book::findOrFail($id);
            $book->fill([
                "title" => $request->title,
                "description" => $request->description
            ]);
            $book->save();
            return response()->json([
                "success" => true,
                "message" => "Data updated!"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => "Book not found"
            ]);
        }

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "user_id" => "required",
            "title" => "required|min:1",
            "description" => "required|min:8"
        ]);

        $userExist = User::where("id",$request->user_id)->first();

        if(!$userExist){
            return response()->json([
                "message" => "Invalid action!"
            ]);
        }

        $create = Book::create([
            "user_id" => $request->user_id,
            "title" => $request->title,
            "description" => $request->description
        ]);

        return response()->json([
            "success" => true,
            "data" => $create
        ]);
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json([
            "success" => true,
            "message" => "Deleted succesfully"
        ]);
    }
}
