<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\NewsCollection;
use App\Http\Resources\Api\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * @OA\Get(
     *      path="/news",
     *      operationId="getNewsList",
     *      tags={"News"},
     *      summary="Get list of news",
     *      description="Returns list of news",
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
     *          @OA\JsonContent(ref="#/components/schemas/NewsResource")
     *       )
     *     )
     */
    public function index()
    {
        return new NewsCollection(News::paginate(10));
    }

    /**
     * @OA\Get(
     *      path="/news/{id}",
     *      operationId="getNewsById",
     *      tags={"News"},
     *      summary="Get news information",
     *      description="Returns news data",
     *      @OA\Parameter(
     *          name="id",
     *          description="News id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/News")
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *      )
     * )
     */
    public function show($id)
    {
        return new NewsResource(News::findOrFail($id));
    }
}
