<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\PhotoCollection;
use App\Http\Resources\Api\PhotoResource;
use App\Models\Photo;
use Illuminate\Http\Request;

class PhotosController extends Controller
{
    /**
     * @OA\Get(
     *      path="/photos",
     *      operationId="getPhotosList",
     *      tags={"Photos"},
     *      summary="Get list of photo albums",
     *      description="Returns list of albums",
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
     *          @OA\JsonContent(ref="#/components/schemas/PhotoResource")
     *       )
     *     )
     */
    public function index()
    {
        return new PhotoCollection(Photo::paginate(10));
    }


    /**
     * @OA\Get(
     *      path="/photos/{id}",
     *      operationId="getPhotoAlbumById",
     *      tags={"Photos"},
     *      summary="Get Photo Album with photos list",
     *      description="Get Photo Album with photos list",
     *      @OA\Parameter(
     *          name="id",
     *          description="Photo album id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/PhotoResource")
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found",
     *          @OA\JsonContent(ref="#/components/schemas/NotFoundException")
     *      )
     * )
     */
    public function show($id)
    {
        return (new PhotoResource(Photo::findOrFail($id)))->withExtra();
    }
}
