<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
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
     *      path="/api/add-blog",
     *      summary="Add Blog",
     *      tags={"Blog"},
     *      operationId="storeBlog",
     *   security={{"bearer_security":{}}},
     * @OA\Response(response=200,description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=406,description="not acceptable", @OA\JsonContent()),
     * @OA\Response(response=500,description="internal server error", @OA\JsonContent()),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                  required={"title"},
     *                  @OA\Property(
     *                      property="title",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="content",
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
    public function storeBlog(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'title' => 'required',
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

        if (Blog::where('title',$request->title)->count() != 0) {
            return response()->json([
                'response_code' => 422,
                'message' => 'The given data was invalid.',
                'errors' => "Blog Title already exist",
                'data' => (object)[]
            ], 422);
        }

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path("blog"), $imageName);
        }

        if($imageName != ""){
            $data = Blog::create([
                'title' => $request->title,
                'content' => $request->content,
                'image' => "blog/".$imageName,
                'status' => $request->status == "true"?1:0,
            ]);
        }

        if ($data) {
            return response()->json([
                'response_code' => 200,
                'message' => 'Blog Added',
                'errors' => (Object)[],
                'data' => $data
            ], 200);
        }else{
            return response()->json([
                'response_code' => 200,
                'message' => 'Blog not Added',
                'errors' => "Blog not added",
                'data' => (Object)[]
            ], 200);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */


    /**
     * @OA\Get(
     *      path="/api/view-blog/{id}",
     *      summary="View Blog",
     *      tags={"Blog"},
     *      operationId="showBlog",
     *      security={{"bearer_security":{}}},
     *      @OA\Parameter(
     *         description="Blog Id",
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
    public function showBlog($id = "",Blog $blog)
    {
        //
        if($id=="," || $id=="" || $id=="{id}"){
            $blog = Blog::all();
        }else{
            $blog = Blog::where('id',$id)->first();
        }
        return response()->json([
            'response_code' => 201,
            'message' => 'Blog',
            'errors' => (Object)[],
            'data' => $blog
        ], 200);

    }



    /**
     * @OA\Get(
     *      path="/api/view-active-blog/{id}",
     *      summary="View Active Blog",
     *      tags={"Blog"},
     *      operationId="showActiveBlog",
     *      security={{"bearer_security":{}}},
     *      @OA\Parameter(
     *         description="Blog Id",
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
    public function showActiveBlog($id = "",Blog $blog)
    {
        //
        if($id=="," || $id=="" || $id=="{id}"){
            $blog = Blog::where("status",1)->get();
        }else{
            $blog = Blog::where("status",1)->where('id',$id)->first();
        }
        return response()->json([
            'response_code' => 201,
            'message' => 'Blog',
            'errors' => (Object)[],
            'data' => $blog
        ], 200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */


    /**
     * @OA\Post(
     *      path="/api/edit-blog/{id}",
     *      summary="Edit Blog",
     *      tags={"Blog"},
     *      operationId="editBlog",
     *      security={{"bearer_security":{}}},
     * @OA\Response(response=200,description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=406,description="not acceptable", @OA\JsonContent()),
     * @OA\Response(response=500,description="internal server error", @OA\JsonContent()),
     *
     *     @OA\Parameter(
     *         description="Blog Id",
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
     *                  required={"title"},
     *                  @OA\Property(
     *                      property="title",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="content",
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
    public function editBlog($id="",Request $request,Blog $blog)
    {
        //

        $validator = Validator::make($request->all(), [
            'title' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response_code' => 422,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
                'data' => (Object)[]
            ], 422);
        }

        if (Blog::where('title',$request->title)->where('id','<>',$id)->count() != 0) {
            return response()->json([
                'response_code' => 422,
                'message' => 'The given data was invalid.',
                'errors' => "Blog Title already exist",
                'data' => (object)[]
            ], 422);
        }

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path("blog"), $imageName);
        }

        $requestData = $request->all();
        if($request->hasFile('image')){
            $requestData['image'] = 'blog/'.$imageName;
        }
        $data = Blog::where('id',$request->id)->update($requestData);

        if ($data) {
            return response()->json([
                'response_code' => 200,
                'message' => 'Blog Updated',
                'errors' => (Object)[],
                'data' => Blog::where("id",$id)->first()
            ], 200);
        }else{
            return response()->json([
                'response_code' => 200,
                'message' => 'Blog not Updated',
                'errors' => "Blog not Updated",
                'data' => (Object)[]
            ], 200);
        }

    }

    /**
     * @OA\Post(
     *      path="/api/edit-blog-status/{id}",
     *      summary="Edit Blog Status",
     *      tags={"Blog"},
     *      operationId="updateBlogStatus",
     *      security={{"bearer_security":{}}},
     * @OA\Response(response=200,description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=406,description="not acceptable", @OA\JsonContent()),
     * @OA\Response(response=500,description="internal server error", @OA\JsonContent()),
     *     @OA\Parameter(
     *         description="Blog Id",
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
    public function updateBlogStatus($id="",Blog $blog)
    {
        //
        $blog = Blog::where('id',$id)->first();

        // return response()->json([$blog[0]->status]);
        if($blog->status == 1){
            $status = 0;
        }else{
            $status = 1;
        }
        $data = Blog::where('id',$id)->update([
            'status' => $status
        ]);

        if ($data) {
            return response()->json([
                'response_code' => 200,
                'message' => 'Blog Status Updated',
                'errors' => (Object)[],
                'data' => Blog::where("id",$id)->first()
            ], 200);
        }else{
            return response()->json([
                'response_code' => 200,
                'message' => 'Blog Stauts not Updated',
                'errors' => "Blog Stauts not Updated",
                'data' => (Object)[]
            ], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */

     /**
     * @OA\Delete(
     *      path="/api/delete-blog/{id}",
     *      summary="Delete Blog",
     *      tags={"Blog"},
     *      operationId="destroyBlog",
     *      security={{"bearer_security":{}}},
     * @OA\Response(response=200,description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=406,description="not acceptable", @OA\JsonContent()),
     * @OA\Response(response=500,description="internal server error", @OA\JsonContent()),
     *     @OA\Parameter(
     *         description="Blog Id",
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
    public function destroyBlog($id="",Blog $blog)
    {
        //
        $data = Blog::where("id",$id)->delete();

        if ($data) {
            return response()->json([
                'response_code' => 200,
                'message' => 'Blog Deleted',
                'errors' => (Object)[],
                'data' => Blog::where("id",$id)->first()
            ], 200);
        }else{
            return response()->json([
                'response_code' => 200,
                'message' => 'Blog not Deleted',
                'errors' => "Blog not Deleted",
                'data' => (Object)[]
            ], 200);
        }
    }
}
