<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
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
     *      path="/api/add-product",
     *      summary="Add Product",
     *      tags={"Product"},
     *      operationId="productStore",
     *   security={{"bearer_security":{}}},
     * @OA\Response(response=200,description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=406,description="not acceptable", @OA\JsonContent()),
     * @OA\Response(response=500,description="internal server error", @OA\JsonContent()),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                  required={"name","price"},
     *                  @OA\Property(
     *                      property="name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="price",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="discount_price",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="text"
     *                  ),
     *                  @OA\Property(
     *                      property="status",
     *                      type="boolean"
     *                  ),
     *                  @OA\Property(
     *                      property="image",
     *                      type="string",
     *                      format="binary"
     *                  )
     *             )
     *         )
     *     )
     *)
     *
     */
    public function productStore(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response_code' => 422,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
                'data' => (object)[]
            ], 422);
        }

        if (Product::where('name',$request->name)->count() != 0) {
            return response()->json([
                'response_code' => 422,
                'message' => 'The given data was invalid.',
                'errors' => "Product name already exist",
                'data' => (object)[]
            ], 422);
        }

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path("product"), $imageName);
        }

        if($imageName != ""){
            $data = Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'description' => $request->description,
                'image' => "product/".$imageName,
                'status' => $request->status == "true"?1:0,
            ]);
        }

        if ($data) {
            return response()->json([
                'response_code' => 200,
                'message' => 'Product Added',
                'errors' => (Object)[],
                'data' => $data
            ], 200);
        }else{
            return response()->json([
                'response_code' => 200,
                'message' => 'Product not Added',
                'errors' => "Product not added",
                'data' => (Object)[]
            ], 200);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *      path="/api/view-product/{id}",
     *      summary="View product",
     *      tags={"Product"},
     *      operationId="productShow",
     *      security={{"bearer_security":{}}},
     *      @OA\Parameter(
     *         description="Product Id",
     *         in="path",
     *         name="id",
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
    public function productShow($id="",Product $product)
    {
        //
        if($id=="," || $id==""){
            $product = Product::all();
        }else{
            $product = Product::where('id',$id)->first();
        }
        return response()->json([
            'response_code' => 201,
            'message' => 'Products',
            'errors' => (Object)[],
            'data' => $product
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */


    /**
     * @OA\Post(
     *      path="/api/edit-product/{id}",
     *      summary="Edit Product",
     *      tags={"Product"},
     *      operationId="productUpdate",
     *   security={{"bearer_security":{}}},
     * @OA\Response(response=200,description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=406,description="not acceptable", @OA\JsonContent()),
     * @OA\Response(response=500,description="internal server error", @OA\JsonContent()),
     *     @OA\Parameter(
     *         description="Product Id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                  @OA\Property(
     *                      property="name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="price",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="discount_price",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="text"
     *                  ),
     *                  @OA\Property(
     *                      property="category",
     *                      type="text"
     *                  ),
     *                  @OA\Property(
     *                      property="subcategory",
     *                      type="text"
     *                  ),
     *                  @OA\Property(
     *                      property="status",
     *                      type="boolean"
     *                  ),
     *                  @OA\Property(
     *                      property="image",
     *                      type="string",
     *                      format="binary"
     *                  )
     *             )
     *         )
     *     )
     *)
     *
     */
    public function productUpdate($id="",Request $request, Product $product)
    {
        //
        if (Product::where('name',$request->name)->where('id','<>',$id)->count() != 0) {
            return response()->json([
                'response_code' => 422,
                'message' => 'The given data was invalid.',
                'errors' => "Product name already exist",
                'data' => (object)[]
            ], 422);
        }

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path("product"), $imageName);
        }

        $requestData = array_filter($request->all());
        $data = Product::where('id',$request->id)->update($requestData);

        if ($data) {
            return response()->json([
                'response_code' => 200,
                'message' => 'Product Updated',
                'errors' => (Object)[],
                'data' => $requestData
            ], 200);
        }else{
            return response()->json([
                'response_code' => 200,
                'message' => 'Product not Updated',
                'errors' => "Product not Updated",
                'data' => (Object)[]
            ], 200);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/edit-product-status/{id}",
     *      summary="Edit Product Status",
     *      tags={"Product"},
     *      operationId="updateProductStatus",
     *      security={{"bearer_security":{}}},
     * @OA\Response(response=200,description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=406,description="not acceptable", @OA\JsonContent()),
     * @OA\Response(response=500,description="internal server error", @OA\JsonContent()),
     *     @OA\Parameter(
     *         description="Product Id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     )
     *)
     *
     */
    public function updateProductStatus($id,Product $category)
    {
        //
        $product = Product::where('id',$id)->first();
        if($product->status == 1){
            $status = 0;
        }else{
            $status = 1;
        }
        $data = Product::where('id',$id)->update([
            'status' => $status
        ]);

        if ($data) {
            return response()->json([
                'response_code' => 200,
                'message' => 'Product Status Updated',
                'errors' => (Object)[],
                'data' => Product::where("id",$id)->first()
            ], 200);
        }else{
            return response()->json([
                'response_code' => 200,
                'message' => 'Product Stauts not Updated',
                'errors' => "Product Stauts not Updated",
                'data' => (Object)[]
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */

     /**
     * @OA\Delete(
     *      path="/api/delete-product/{id}",
     *      summary="Delete Product",
     *      tags={"Product"},
     *      operationId="productDestroy",
     *      security={{"bearer_security":{}}},
     * @OA\Response(response=200,description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=406,description="not acceptable", @OA\JsonContent()),
     * @OA\Response(response=500,description="internal server error", @OA\JsonContent()),
     *     @OA\Parameter(
     *         description="Product Id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     )
     *)
     *
     */
    public function productDestroy($id,Product $product)
    {
        //
        $data = Product::where("id",$id)->delete();

        if ($data) {
            return response()->json([
                'response_code' => 200,
                'message' => 'Product Deleted',
                'errors' => (Object)[],
                'data' => Product::where("id",$id)->first()
            ], 200);
        }else{
            return response()->json([
                'response_code' => 200,
                'message' => 'Product not Deleted',
                'errors' => "Product not Deleted",
                'data' => (Object)[]
            ], 200);
        }
    }
}
