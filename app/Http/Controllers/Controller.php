<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(title="E-Commerce", version="0.0.1")
 */
class Controller extends BaseController
{
    /**
     * @OA\OpenApi(
     *     @OA\Info(
     *         version="0.0.1",
     *         title="E-commerce API,
     *         description="E-commerce APIs for customers and sellers",
     *     )
     * )
     */

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
