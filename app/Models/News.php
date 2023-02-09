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
 *     )
 * )
 */
class News extends Model
{
    use HasFactory;

    const CREATED_AT = 'date';

    /**
     * @OA\Property(
     *     title="ID",
     *     description="ID",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    private $id;

    /**
     * @OA\Property(
     *     title="Date",
     *     description="Created at",
     *     example="2020-01-27 17:50:45",
     *     format="datetime",
     *     type="string"
     * )
     *
     * @var \DateTime
     */
    public $date;


    /**
     * @OA\Property(
     *      title="Text",
     *      description="News text",
     *      example="News content text"
     * )
     *
     * @var string
     */
    public $text;

    /**
     * @OA\Property(
     *      title="Text EN",
     *      description="News english text",
     *      example="News text english version"
     * )
     *
     * @var string
     */
    public $text_en;

    public function getLocalizedText() {
        if(App::isLocale('en') && $this->text_en) {
            return $this->text_en;
        }
        return $this->text;
    }
}
