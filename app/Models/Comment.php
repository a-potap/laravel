<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Blog comments",
 *     description="Blog comments model",
 *     @OA\Xml(
 *         name="Comment"
 *     )
 * )
 */
class Comment extends Model
{
    use HasFactory;

    protected $table = 'blog_coments';

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
     *      description="Comment text",
     *      example="Some comment to any post"
     * )
     *
     * @var string
     */
    public $text;

    protected $hidden = [
        'idpost',
        'iduser',
    ];
}
