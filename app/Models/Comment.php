<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'blog_coments';

    const CREATED_AT = 'date';

    protected $hidden = [
        'idpost',
        'iduser',
    ];
}
