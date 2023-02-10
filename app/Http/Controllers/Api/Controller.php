<?php

namespace App\Http\Controllers\Api;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="A-POTAP API Documentation",
 *      description="Describe API methods for information",
 *      @OA\Contact(
 *          email="a-potap@mail.ru"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="A_POTAP API Server"
 * )

 *
 * @OA\Tag(
 *     name="A-POTAP BLOG",
 *     description="API Endpoints of "
 * )
 *
 * @OA\Schemas (
 * @OA\Schema (
 *     schema="NotFoundException",
 *     title="NotFoundException",
 *     description="Not Found Exception data",
 *     @OA\Xml(
 *         name="NotFoundException"
 *     ) ,
 *     @OA\Property(
 *          property="message",
 *          title="Message",
 *          description="Error text",
 *          format="string",
 *          example="Record not found.",
 *          type="string"
 *      )
 * )
 * )
 *
 *

 */
class Controller extends BaseController
{
    use ValidatesRequests;
}
