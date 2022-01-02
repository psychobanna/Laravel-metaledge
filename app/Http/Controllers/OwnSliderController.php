<?php

namespace App\Http\Controllers;

use App\Models\ownSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OwnSliderController extends Controller
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
     *      path="/api/add-banner",
     *      summary="Add banner",
     *      tags={"Banner"},
     *      operationId="bannerstore",
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
     *                      property="type",
     *                      type="text"
     *                  ),
     *                  @OA\Property(
     *                      property="link",
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
    public function bannerstore(Request $request)
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

        if (ownSlider::where('title',$request->title)->count() != 0) {
            return response()->json([
                'response_code' => 422,
                'message' => 'The given data was invalid.',
                'errors' => "Title already exist",
                'data' => (object)[]
            ], 422);
        }

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path("banner"), $imageName);
        }

        if($imageName != ""){
            $data = ownSlider::create([
                'type' => $request->type,
                'title' => $request->title,
                'link' => $request->link,
                'content' => $request->content,
                'image' => "banner/".$imageName,
                'status' => $request->status == "true"?1:0,
            ]);
        }

        if ($data) {
            return response()->json([
                'response_code' => 200,
                'message' => 'Banner Added',
                'errors' => (Object)[],
                'data' => $data
            ], 200);
        }else{
            return response()->json([
                'response_code' => 200,
                'message' => 'Banner not Added',
                'errors' => "Banner not added",
                'data' => (Object)[]
            ], 200);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ownSlider  $ownSlider
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *      path="/api/view-banner/{id}",
     *      summary="banner",
     *      tags={"Banner"},
     *      operationId="bannershow",
     *      security={{"bearer_security":{}}},
     *      @OA\Parameter(
     *         description="Banner Id",
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
    public function bannershow($id="",ownSlider $ownSlider)
    {
        //
        if($id=="" || $id=="," || $id=="{id}"){
            $sliders = $ownSlider->all();
        }else{
            $sliders = $ownSlider->where('id',$id)->first();
        }

        return response()->json([
            'response_code' => 201,
            'message' => 'Bnners',
            'errors' => (Object)[],
            'data' => $sliders
        ], 200);
    }

    /**
     * @OA\Get(
     *      path="/api/view-active-banner/{id}",
     *      summary="banner",
     *      tags={"Banner"},
     *      operationId="bannerActiveShow",
     *      security={{"bearer_security":{}}},
     *      @OA\Parameter(
     *         description="Banner Id",
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
    public function bannerActiveShow($id="",ownSlider $ownSlider)
    {
        //
        if($id=="" || $id=="," || $id=="{id}"){
            $sliders = $ownSlider->where('status',1)->get();
        }else{
            $sliders = $ownSlider->where('status',1)->where('id',$id)->first();
        }

        return response()->json([
            'response_code' => 201,
            'message' => 'Bnners',
            'errors' => (Object)[],
            'data' => $sliders
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ownSlider  $ownSlider
     * @return \Illuminate\Http\Response
     */
    public function edit(ownSlider $ownSlider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ownSlider  $ownSlider
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Post(
     *      path="/api/edit-banner/{id}",
     *      summary="Edit Banner",
     *      tags={"Banner"},
     *      operationId="bannerupdate",
     *      security={{"bearer_security":{}}},
     * @OA\Response(response=200,description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=406,description="not acceptable", @OA\JsonContent()),
     * @OA\Response(response=500,description="internal server error", @OA\JsonContent()),
     *
     *     @OA\Parameter(
     *         description="Banner Id",
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
     *                  @OA\Property(
     *                      property="title",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="content",
     *                      type="text"
     *                  ),
     *                  @OA\Property(
     *                      property="type",
     *                      type="text"
     *                  ),
     *                  @OA\Property(
     *                      property="link",
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
    public function bannerupdate($id,Request $request, ownSlider $ownSlider)
    {
        //

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

        if (ownSlider::where('title',$request->title)->where('id','<>',$id)->count() != 0) {
            return response()->json([
                'response_code' => 422,
                'message' => 'The given data was invalid.',
                'errors' => "Banner Title already exist",
                'data' => (object)[]
            ], 422);
        }

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path("banner"), $imageName);
        }

        $requestData = array_filter($request->all());
        if($request->hasFile('image')){
            $requestData['image'] = 'banner/'.$imageName;
        }
        $data = ownSlider::where('id',$request->id)->update($requestData);

        if ($data) {
            return response()->json([
                'response_code' => 200,
                'message' => 'Banner Updated',
                'errors' => (Object)[],
                'data' => ownSlider::where("id",$id)->first()
            ], 200);
        }else{
            return response()->json([
                'response_code' => 200,
                'message' => 'Banner not Updated',
                'errors' => "Banner not Updated",
                'data' => (Object)[]
            ], 200);
        }
    }


    /**
     * @OA\Post(
     *      path="/api/edit-banner-status/{id}",
     *      summary="Edit Banner Status",
     *      tags={"Banner"},
     *      operationId="bannerUpdateStatus",
     *      security={{"bearer_security":{}}},
     * @OA\Response(response=200,description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=406,description="not acceptable", @OA\JsonContent()),
     * @OA\Response(response=500,description="internal server error", @OA\JsonContent()),
     *     @OA\Parameter(
     *         description="Banner Id",
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
    public function bannerUpdateStatus($id,ownSlider $ownSlider)
    {
        //

        $ownSlider = ownSlider::where('id',$id)->first();

        if($ownSlider->status == 1){
            $status = 0;
        }else{
            $status = 1;
        }
        $data = ownSlider::where('id',$id)->update([
            'status' => $status
        ]);

        if ($data) {
            return response()->json([
                'response_code' => 200,
                'message' => 'Banner Status Updated',
                'errors' => (Object)[],
                'data' => ownSlider::where("id",$id)->first()
            ], 200);
        }else{
            return response()->json([
                'response_code' => 200,
                'message' => 'Banner Stauts not Updated',
                'errors' => "Banner Stauts not Updated",
                'data' => (Object)[]
            ], 200);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ownSlider  $ownSlider
     * @return \Illuminate\Http\Response
     */

     /**
     * @OA\Delete(
     *      path="/api/delete-banner/{id}",
     *      summary="Delete Banner",
     *      tags={"Banner"},
     *      operationId="bannerDestroy",
     *      security={{"bearer_security":{}}},
     * @OA\Response(response=200,description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=406,description="not acceptable", @OA\JsonContent()),
     * @OA\Response(response=500,description="internal server error", @OA\JsonContent()),
     *     @OA\Parameter(
     *         description="Banner Id",
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
    public function bannerDestroy($id="",ownSlider $ownSlider)
    {
        //
        $data = ownSlider::where("id",$id)->delete();

        if ($data) {
            return response()->json([
                'response_code' => 200,
                'message' => 'Banner Deleted',
                'errors' => (Object)[],
                'data' => ownSlider::where("id",$id)->first()
            ], 200);
        }else{
            return response()->json([
                'response_code' => 200,
                'message' => 'Banner not Deleted',
                'errors' => "Banner not Deleted",
                'data' => (Object)[]
            ], 200);
        }
    }
}
