<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class News extends Model
{
    use HasFactory;

    const CREATED_AT = 'date';

    public function getLocalizedText() {
        if(App::isLocale('en') && $this->text_en) {
            return $this->text_en;
        }
        return $this->text;
    }
}
