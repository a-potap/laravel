<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\StoreCommentRequest;
use App\Http\Resources\Api\CommentCollection;
use App\Http\Resources\Api\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Response;

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

    /**
     * @OA\Post(
     *      path="/blog/{id}/comments",
     *      operationId="getBlogCommentsStore",
     *      tags={"Blog"},
     *      summary="Store post comment",
     *      description="Returns comment data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Blog id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreCommentRequest")
     *      ),
     *     @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Comment")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function store(StoreCommentRequest $request)
    {
        $comment = Comment::create($request->all());

        return (new CommentResource($comment))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
