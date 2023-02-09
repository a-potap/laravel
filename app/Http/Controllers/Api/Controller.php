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
 */
class Controller extends BaseController
{
    use ValidatesRequests;
}
