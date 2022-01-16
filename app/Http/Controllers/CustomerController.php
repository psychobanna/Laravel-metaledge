<?php

namespace App\Http\Controllers;

use App\Models\customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{

    /**
     * @OA\Post(
     *      path="/api/customer-register",
     *      summary="Customer Registration",
     *      tags={"Customer"},
     *      operationId="userRegister",
     * @OA\Response(response=200,description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=406,description="not acceptable", @OA\JsonContent()),
     * @OA\Response(response=500,description="internal server error", @OA\JsonContent()),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                  required={"name"},
     *                  @OA\Property(
     *                      property="name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="username",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="text"
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="text"
     *                  ),
     *                  @OA\Property(
     *                      property="contact",
     *                      type="text"
     *                  )
     *             )
     *         )
     *     )
     *)
     *
     */
    public function userRegister(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required',
            'email' => 'required|email|unique:customers',
            'contact' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response_code' => 422,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
                'data' => (object)[]
            ], 422);
        }
        $requestData = $request->all();
        $requestData['password'] = Hash::make($requestData['password']);
        $requestData['username'] = microtime();
        customer::create($requestData);
        return response()->json(customer::all());
    }

    /**
     * @OA\Post(
     *      path="/api/customer-login",
     *      summary="Customer Login",
     *      tags={"Customer"},
     *      operationId="userLogin",
     * @OA\Response(response=200,description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=406,description="not acceptable", @OA\JsonContent()),
     * @OA\Response(response=500,description="internal server error", @OA\JsonContent()),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                  @OA\Property(
     *                      property="email",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="text"
     *                  )
     *             )
     *         )
     *     )
     *)
     *
     */
    public function userLogin(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response_code' => 422,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
                'data' => (object)[]
            ], 422);
        }

        if(customer::where('email',$request->email)->count() == 0){
            return response()->json([
                'response_code' => 422,
                'message' => 'The given data was invalid.',
                'errors' => "email invalid",
                'data' => (object)[]
            ], 422);
        }

        $customer = customer::where('email',$request->email)->first();

        // print_r(Hash::check($customer->password,$request->password));

        if(!Hash::check($request->password,$customer->password)){
            return response()->json([
                'response_code' => 422,
                'message' => 'The given data was invalid.',
                'errors' => "Password invalid",
                'data' => (object)[]
            ], 422);
        }

        $customer['token'] = $customer->createToken('myapptoken')->plainTextToken;

        return response()->json([
            'response_code' => 200,
            'message' => "Login successfully",
            'errors' => (object)[],
            'data' => $customer
        ], 200);
    }

}
