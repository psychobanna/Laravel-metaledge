<?php

namespace App\Http\Controllers;

use App\Models\Reviews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewsController extends Controller
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
     *      path="/api/add-review",
     *      summary="Add Review",
     *      tags={"Review"},
     *      operationId="storeReview",
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
     *                      property="product_id",
     *                      type="number"
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="comment",
     *                      type="text"
     *                  ),
     *                  @OA\Property(
     *                      property="rating",
     *                      type="number"
     *                  )
     *             )
     *         )
     *     )
     *)
     *
     */
    public function storeReview(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'name' => 'required',
            'email' => 'required',
            'rating' => 'required',
            'comment' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response_code' => 422,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
                'data' => (object)[]
            ], 422);
        }

        $data = Reviews::create($request->all());
        if($data){
            return response()->json([
                'response_code' => 200,
                'message' => 'Review Added',
                'errors' => "Review added",
                'data' => (Object)[]
            ], 200);
        }else{
            return response()->json([
                'response_code' => 422,
                'message' => 'Review not Added',
                'errors' => "Review not added",
                'data' => (Object)[]
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reviews  $reviews
     * @return \Illuminate\Http\Response
     */
    public function show(Reviews $reviews)
    {
        //
    }


    /**
     * @OA\Get(
     *      path="/api/view-review/{product_id}",
     *      summary="View Review",
     *      tags={"Review"},
     *      operationId="showReview",
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
    public function showReview($product_id="",Reviews $review)
    {
        //
        $reviews = Reviews::where("product_id",$product_id)->get();

        return response()->json([
            'response_code' => 201,
            'message' => 'Reviews',
            'errors' => (Object)[],
            'data' => $reviews
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reviews  $reviews
     * @return \Illuminate\Http\Response
     */
    public function edit(Reviews $reviews)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reviews  $reviews
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reviews $reviews)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reviews  $reviews
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reviews $reviews)
    {
        //
    }
}
