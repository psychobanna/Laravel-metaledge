<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



/**
 * @OA\Swagger(
 *     schemes={"http"},
 *     host=API_HOST,
 *     basePath="/",
 *     @OA\Info(
 *         version="1.0.0",
 *         title="THE METAL EDGE",
 *         description="The Metal Edge API",
 *         termsOfService="",
 *         @OA\Contact(
 *             email="sumersingh1997.ssh@gmail.com"
 *         ),
 *     ),
 * )
 */
class AngularController extends Controller
{
    //
    public function index()
    {
        return view('angular');
    }
}
