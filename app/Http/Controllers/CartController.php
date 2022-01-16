<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    /**
     * @OA\Post(
     *      path="/api/add-cart",
     *      summary="Add Cart",
     *      tags={"Cart"},
     *      operationId="storeCart",
     * @OA\Response(response=200,description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=406,description="not acceptable", @OA\JsonContent()),
     * @OA\Response(response=500,description="internal server error", @OA\JsonContent()),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                  required={"customer","product","qty"},
     *                  @OA\Property(
     *                      property="customer",
     *                      type="number"
     *                  ),
     *                  @OA\Property(
     *                      property="product",
     *                      type="number"
     *                  ),
     *                  @OA\Property(
     *                      property="qty",
     *                      type="number"
     *                  )
     *             )
     *         )
     *     )
     *)
     *
     */
    public function storeCart(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'customer' => 'required',
            'product' => 'required',
            'qty' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response_code' => 422,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
                'data' => (object)[]
            ], 422);
        }

        $count = Cart::where("product",$request->customer)->where("product",$request->product)->count();
        if($count != 1){
            $data = Cart::create([
                'customer' => $request->customer,
                'product' => $request->product,
                'qty' => $request->qty,
            ]);
        }else{
            $cartItem = Cart::where("product",$request->customer)->where("product",$request->product)->first();

            $data = Cart::where("product",$request->customer)->where("product",$request->product)->Update([
                'qty' => $request->qty + $cartItem->qty,
            ]);
        }

        $cartItem = Cart::where("product",$request->customer)->where("product",$request->product)->first();

        if($data){
            return response()->json([
                'response_code' => 200,
                'message' => 'Cart Added',
                'errors' => (Object)[],
                'data' => $cartItem
            ], 200);
        }else{
            return response()->json([
                'response_code' => 200,
                'message' => 'Cart not Added',
                'errors' => (Object)[],
                'data' => $cartItem
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */


    /**
     * @OA\Get(
     *      path="/api/view-cart/{id}/{product_id}",
     *      summary="View Cart",
     *      tags={"Cart"},
     *      operationId="showCart",
     *      @OA\Parameter(
     *         description="Customer Id",
     *         in="path",
     *         name="id",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *      ),
     *      @OA\Parameter(
     *         description="Product Id",
     *         in="path",
     *         name="product_id",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *      ),
     *      @OA\Response(response=201,description="successful operation", @OA\JsonContent()),
     *      @OA\Response(response=406,description="not acceptable", @OA\JsonContent()),
     *      @OA\Response(response=500,description="internal server error", @OA\JsonContent()),
     *)
     */
    public function showCart($id="",$product_id="",Cart $cart)
    {
        //
        if($id=="," || $id==""){
            return response()->json([
                'response_code' => 422,
                'message' => 'Cart',
                'errors' => [
                    "id"=>"Customer id not available"
                ],
                'data' => (Object)[]
            ], 422);
        }else{
            if($product_id == "" || $product_id =="," || $product_id =="{product_id}"){
                $cart = Cart::where("customer",$id)->get();
                return response()->json([
                    'response_code' => 200,
                    'message' => 'Cart',
                    'errors' => (Object)[],
                    'data' => $cart
                ], 200);
            }else{
                $cart = Cart::where("product",$product_id)->where("customer",$id)->get();
                return response()->json([
                    'response_code' => 200,
                    'message' => 'Cart',
                    'errors' => (Object)[],
                    'data' => $cart
                ], 200);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        //
    }
}
