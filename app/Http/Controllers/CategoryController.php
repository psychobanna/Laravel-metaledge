<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
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
     *      path="/api/add-category",
     *      summary="Add Category",
     *      tags={"Category"},
     *      operationId="store",
     *   security={{"bearer_security":{}}},
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
     *                      property="parent_id",
     *                      type="number"
     *                  ),
     *                  @OA\Property(
     *                      property="name",
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
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'name' => 'required',
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

        if (Category::where('name',$request->name)->count() != 0) {
            return response()->json([
                'response_code' => 422,
                'message' => 'The given data was invalid.',
                'errors' => "Category already exist",
                'data' => (object)[]
            ], 422);
        }

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path("category"), $imageName);
        }

        if($imageName != ""){
            $data = Category::create([
                'parent_id' => !empty($request->parent_id) ? $request->parent_id : 0,
                'name' => $request->name,
                'description' => $request->description,
                'image' => "category/".$imageName,
                'status' => $request->status == "true"?1:0,
            ]);
        }

        if ($data) {
            return response()->json([
                'response_code' => 200,
                'message' => 'Category Added',
                'errors' => (Object)[],
                'data' => $data
            ], 200);
        }else{
            return response()->json([
                'response_code' => 200,
                'message' => 'Category not Added',
                'errors' => "Category not added",
                'data' => (Object)[]
            ], 200);
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */


    /**
     * @OA\Get(
     *      path="/api/view-category/{id}/{parent_id}",
     *      summary="View Category",
     *      tags={"Category"},
     *      operationId="show",
     *      security={{"bearer_security":{}}},
     *      @OA\Parameter(
     *         description="Category Id",
     *         in="path",
     *         name="id",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *      ),
     *      @OA\Parameter(
     *         description="Parent Id",
     *         in="path",
     *         name="parent_id",
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
    public function show($id="",$parent_id="",Category $category)
    {
        //
        if($id=="," || $id==""){
            $category = Category::all();

        }else{
            if($parent_id == ""){
                $category = Category::where('id',$id)->first();
            }else{
                $category = Category::where("parent_id",$parent_id)->get();
            }
        }
        return response()->json([
            'response_code' => 201,
            'message' => 'Categories',
            'errors' => (Object)[],
            'data' => $category
        ], 200);

    }



    /**
     * @OA\Get(
     *      path="/api/view-active-category",
     *      summary="View Active Category",
     *      tags={"Category"},
     *      operationId="showActiveCategory",
     *      @OA\Parameter(
     *         description="Parent Id",
     *         in="path",
     *         name="parent_id",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *      ),
     *      @OA\Parameter(
     *         description="Category Id",
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
    public function showActiveCategory($id="",$parent_id="",Category $category)
    {
        //
        if($id=="," || $id==""){
            if($parent_id == "" || $parent_id == ","){
                $category = Category::all();
            }else{
                $category = Category::where("parent_id",$parent_id)->get();
            }
        }else{
            if($parent_id == ""){
                $category = Category::where('id',$id)->first();
            }else{
                $category = Category::where("parent_id",$parent_id)->get();
            }
        }
        return response()->json([
            'response_code' => 201,
            'message' => 'Categories',
            'errors' => (Object)[],
            'data' => $category
        ], 200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Post(
     *      path="/api/edit-category/{id}",
     *      summary="Edit Category",
     *      tags={"Category"},
     *      operationId="update",
     *      security={{"bearer_security":{}}},
     * @OA\Response(response=200,description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=406,description="not acceptable", @OA\JsonContent()),
     * @OA\Response(response=500,description="internal server error", @OA\JsonContent()),
     *
     *     @OA\Parameter(
     *         description="Category Id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                  required={"name"},
     *                  @OA\Property(
     *                      property="parent_id",
     *                      type="number"
     *                  ),
     *                  @OA\Property(
     *                      property="name",
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
    public function update($id,Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            // 'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response_code' => 422,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
                'data' => (Object)[]
            ], 422);
        }

        if (Category::where('name',$request->name)->where('id','<>',$id)->count() != 0) {
            return response()->json([
                'response_code' => 422,
                'message' => 'The given data was invalid.',
                'errors' => "Category already exist",
                'data' => (object)[]
            ], 422);
        }

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path("category"), $imageName);
        }

        $requestData = $request->all();
        if($request->hasFile('image')){
            $requestData['image'] = 'category/'.$imageName;
        }
        $data = Category::where('id',$request->id)->update($requestData);

        if ($data) {
            return response()->json([
                'response_code' => 200,
                'message' => 'Category Updated',
                'errors' => (Object)[],
                'data' => Category::where("id",$id)->first()
            ], 200);
        }else{
            return response()->json([
                'response_code' => 200,
                'message' => 'Category not Updated',
                'errors' => "Category not Updated",
                'data' => (Object)[]
            ], 200);
        }

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Post(
     *      path="/api/edit-category-status/{id}",
     *      summary="Edit Category Status",
     *      tags={"Category"},
     *      operationId="updateStatus",
     *      security={{"bearer_security":{}}},
     * @OA\Response(response=200,description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=406,description="not acceptable", @OA\JsonContent()),
     * @OA\Response(response=500,description="internal server error", @OA\JsonContent()),
     *     @OA\Parameter(
     *         description="Category Id",
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
    public function updateStatus($id,Category $category)
    {
        //

        $category = Category::where('id',$id)->first();

        // return response()->json([$category[0]->status]);
        if($category->status == 1){
            $status = 0;
        }else{
            $status = 1;
        }
        $data = Category::where('id',$id)->update([
            'status' => $status
        ]);

        if ($data) {
            return response()->json([
                'response_code' => 200,
                'message' => 'Category Status Updated',
                'errors' => (Object)[],
                'data' => Category::where("id",$id)->first()
            ], 200);
        }else{
            return response()->json([
                'response_code' => 200,
                'message' => 'Category Stauts not Updated',
                'errors' => "Category Stauts not Updated",
                'data' => (Object)[]
            ], 200);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */

     /**
     * @OA\Delete(
     *      path="/api/delete-category/{id}",
     *      summary="Delete Category",
     *      tags={"Category"},
     *      operationId="destroy",
     *      security={{"bearer_security":{}}},
     * @OA\Response(response=200,description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=406,description="not acceptable", @OA\JsonContent()),
     * @OA\Response(response=500,description="internal server error", @OA\JsonContent()),
     *     @OA\Parameter(
     *         description="Category Id",
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
    public function destroy($id,Category $category)
    {
        //
        $data = Category::where("id",$id)->delete();

        if ($data) {
            return response()->json([
                'response_code' => 200,
                'message' => 'Category Deleted',
                'errors' => (Object)[],
                'data' => Category::where("id",$id)->first()
            ], 200);
        }else{
            return response()->json([
                'response_code' => 200,
                'message' => 'Category not Deleted',
                'errors' => "Category not Deleted",
                'data' => (Object)[]
            ], 200);
        }

    }
}
