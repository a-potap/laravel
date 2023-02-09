<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\CommentCollection;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    /**
     * @OA\Get(
     *      path="/blog/{id}/comments",
     *      operationId="getBlogCommentsList",
     *      tags={"Blog"},
     *      summary="Get list of blog post comments",
     *      description="Returns list of comments",
     *      @OA\Parameter(
     *          name="id",
     *          description="Blog id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          description="page number",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CommentResource")
     *       )
     *     )
     */
    public function index(int $blog_id)
    {
        return new CommentCollection(Comment::where('blog_id', $blog_id)->orderBy('date')->paginate(10));
    }
}
