<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blog';

    const CREATED_AT = 'date';

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
