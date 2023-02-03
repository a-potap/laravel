<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blog';

    const CREATED_AT = 'date';

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getLocalizedTitle() {
        if(App::isLocale('en') && $this->title_en) {
            return $this->title_en;
        }
        return $this->title;
    }
    public function getLocalizedText() {
        if(App::isLocale('en') && $this->text_en) {
            return $this->text_en;
        }
        return $this->text;
    }
}
