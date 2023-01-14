<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    
    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'body',
        'rating'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
