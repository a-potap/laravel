<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

/**
 * @OA\Schema(
 *     title="Blog",
 *     description="Blog model",
 *     @OA\Xml(
 *         name="Blog"
 *     ),
 *     @OA\Property(
 *          property="id",
 *          title="ID",
 *          description="ID",
 *          format="int64",
 *          example=1,
 *          type="integer"
 *      ),
 *     @OA\Property(
 *          property="date",
 *          title="Date",
 *          description="Created at",
 *          format="datetime",
 *          example="2020-01-27 17:50:45",
 *          type="string"
 *      ),
 *     @OA\Property(
 *          property="title",
 *          title="Title",
 *          description="Blog title",
 *          example="This is post title",
 *          type="string"
 *      ),
 *     @OA\Property(
 *          property="title_en",
 *          title="Title EN",
 *          description="Blog title  english version",
 *          example="This is post title",
 *          type="string"
 *      ),
 *     @OA\Property(
 *          property="text",
 *          title="Text",
 *          description="Post content text",
 *          example="Long text should be here",
 *          type="string"
 *      ),
 *     @OA\Property(
 *          property="text_en",
 *          title="Text EN",
 *          description="Post content text english version",
 *          example="Long text should be here",
 *          type="string"
 *      )
 * )
 */
class Blog extends Model
{
    use HasFactory;

    protected $table = 'blog';

    const CREATED_AT = 'date';

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getLocalizedTitle()
    {
        if (App::isLocale('en') && $this->title_en) {
            return $this->title_en;
        }
        return $this->title;
    }

    public function getLocalizedText()
    {
        if (App::isLocale('en') && $this->text_en) {
            return $this->text_en;
        }
        return $this->text;
    }
}
