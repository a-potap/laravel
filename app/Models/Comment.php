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
 *          property="user",
 *          title="User name",
 *          description="User name or nickname",
 *          example="Guest",
 *          type="string"
 *      ),
 *     @OA\Property(
 *          property="text",
 *          title="Comment text",
 *          description="Comment or message from user",
 *          example="Some comment text",
 *          type="string"
 *      )
 * )
 */
class Comment extends Model
{
    use HasFactory;

    protected $table = 'blog_coments';

    const CREATED_AT = 'date';
    const UPDATED_AT = null;

    protected $fillable = ['iduser', 'text', 'blog_id'];
}
