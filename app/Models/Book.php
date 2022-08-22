<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = ["title","description", "user_id"];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function coment()
    {
        return $this->hasMany(Comment::class, "post_id", "id");
    }
}
