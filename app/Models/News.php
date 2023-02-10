<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

/**
 * @OA\Schema(
 *     title="News",
 *     description="News model",
 *     @OA\Xml(
 *         name="News"
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
 *          property="text",
 *          title="Text",
 *          description="News text",
 *          example="Some news text",
 *          type="string"
 *      ),
 *     @OA\Property(
 *          property="text_en",
 *          title="Text EN",
 *          description="News text english version",
 *          example="TSome news text",
 *          type="string"
 *      )
 * )
 */
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
