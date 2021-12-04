<?php

namespace App\Http\Controllers;

use App\Models\Pages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PagesController extends Controller
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
     *      path="/api/add-page",
     *      summary="Add Page",
     *      tags={"Page"},
     *      operationId="storePages",
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
     *                      property="description",
     *                      type="text"
     *                  ),
     *                  @OA\Property(
     *                      property="menu",
     *                      type="string"
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
    public function storePages(Request $request)
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

        if (Pages::where('title',$request->title)->count() != 0) {
            return response()->json([
                'response_code' => 422,
                'message' => 'The given data was invalid.',
                'errors' => "Page already exist",
                'data' => (object)[]
            ], 422);
        }

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path("page"), $imageName);
        }

        if($imageName != ""){
            $data = Pages::create([
                'title' => $request->title,
                'description' => $request->description?$request->description:'',
                'menu' => $request->menu?$request->menu:0,
                'image' => "page/".$imageName,
                'status' => $request->status == "true"?1:0,
            ]);
        }

        if ($data) {
            return response()->json([
                'response_code' => 200,
                'message' => 'Page Added',
                'errors' => (Object)[],
                'data' => $data
            ], 200);
        }else{
            return response()->json([
                'response_code' => 200,
                'message' => 'Page not Added',
                'errors' => "Page not added",
                'data' => (Object)[]
            ], 200);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pages  $pages
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *      path="/api/view-page/{id}/",
     *      summary="View Page",
     *      tags={"Page"},
     *      operationId="showPages",
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
     *      @OA\Response(response=201,description="successful operation", @OA\JsonContent()),
     *      @OA\Response(response=406,description="not acceptable", @OA\JsonContent()),
     *      @OA\Response(response=500,description="internal server error", @OA\JsonContent()),
     *)
     */
    public function showPages($id="",Pages $pages)
    {
        //
        if($id=="," || $id==""){
            $page = Pages::all();

        }else{
            $page = Pages::where('id',$id)->first();
        }
        return response()->json([
            'response_code' => 201,
            'message' => 'Pages',
            'errors' => (Object)[],
            'data' => $page
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pages  $pages
     * @return \Illuminate\Http\Response
     */


    /**
     * @OA\Post(
     *      path="/api/edit-page/{id}",
     *      summary="Edit Page",
     *      tags={"Page"},
     *      operationId="editPage",
     *      security={{"bearer_security":{}}},
     * @OA\Response(response=200,description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=406,description="not acceptable", @OA\JsonContent()),
     * @OA\Response(response=500,description="internal server error", @OA\JsonContent()),
     *     @OA\Parameter(
     *         description="Page Id",
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
     *                      property="title",
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
    public function editPage($id="",Request $request,Pages $pages)
    {
        //
        if (Pages::where('title',$request->title)->where("id","!=",$id)->count() != 0) {
            return response()->json([
                'response_code' => 422,
                'message' => 'The given data was invalid.',
                'errors' => "Page already exist",
                'data' => (object)[]
            ], 422);
        }

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path("page"), $imageName);
        }

        $requestData = array_filter($request->all());
        if ($request->hasFile('image')) {
            $requestData['image'] = "page/".$imageName;
        }


        $data = Pages::where("id",$id)->update($requestData);
        if($data){
            $data = Pages::where("id",$id)->first();
        }

        if ($data) {
            return response()->json([
                'response_code' => 200,
                'message' => 'Page Added',
                'errors' => (Object)[],
                'data' => $data
            ], 200);
        }else{
            return response()->json([
                'response_code' => 200,
                'message' => 'Page not Added',
                'errors' => "Page not added",
                'data' => (Object)[]
            ], 200);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pages  $pages
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pages $pages)
    {
        //
    }

    /**
     * @OA\Post(
     *      path="/api/edit-page-status/{id}",
     *      summary="Edit Page Status",
     *      tags={"Page"},
     *      operationId="updatePageStatus",
     *      security={{"bearer_security":{}}},
     * @OA\Response(response=200,description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=406,description="not acceptable", @OA\JsonContent()),
     * @OA\Response(response=500,description="internal server error", @OA\JsonContent()),
     *     @OA\Parameter(
     *         description="Page Id",
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
    **/
    public function updatePageStatus($id,Pages $page)
    {
        //
        $page = Pages::where('id',$id)->first();

        if($page->status == 1){
            $status = 0;
        }else{
            $status = 1;
        }
        $data = Pages::where('id',$id)->update([
            'status' => $status
        ]);

        if($data){
            return response()->json([
                'response_code' => 200,
                'message' => 'Page Status Updated',
                'errors' => (Object)[],
                'data' => Pages::where("id",$id)->first()
            ], 200);
        }else{
            return response()->json([
                'response_code' => 200,
                'message' => 'Page Stauts not Updated',
                'errors' => "Page Stauts not Updated",
                'data' => (Object)[]
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pages  $pages
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pages $pages)
    {
        //
    }
}
