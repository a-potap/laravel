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
 *     ),
 *     @OA\Property(
 *          property="id",
 *          title="ID",
 *          description="ID",
 *          format="int64",
 *          example="1",
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
 *          description="Comment text",
 *          example="Some comment to any post",
 *          type="string"
 *      )
 * )
 */
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
