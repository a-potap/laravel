<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\BlogCollection;
use App\Http\Resources\Api\BlogResource;
use App\Models\Blog;

class BlogController extends Controller
{
    /**
     * @OA\Get(
     *      path="/blog",
     *      operationId="getBlogList",
     *      tags={"Blog"},
     *      summary="Get list of blog posts",
     *      description="Returns list of posts",
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
     *          @OA\JsonContent(ref="#/components/schemas/BlogResource")
     *       )
     *     )
     */
    public function index()
    {
        return new BlogCollection(Blog::paginate(10));
    }


    /**
     * @OA\Get(
     *      path="/blog/{id}",
     *      operationId="getBlogById",
     *      tags={"Blog"},
     *      summary="Get blog post information",
     *      description="Returns blog post data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Blog id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Blog")
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *      )
     * )
     */
    public function show($id)
    {
        return (new BlogResource(Blog::findOrFail($id)))->withExtra();
    }
}
