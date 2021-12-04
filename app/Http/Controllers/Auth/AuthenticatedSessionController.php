<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */

     /**
     * @OA\Post(
     *      path="/api/login",
     *      summary="Login",
     *      tags={"Admin"},
     *      operationId="store",
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
     *                      type="number"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string"
     *                  )
     *             )
     *         )
     *     )
     *)
     *
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        // $request->session()->regenerate();

        $token = $request->user()->createToken('myapptoken')->plainTextToken;
        // return redirect()->intended(RouteServiceProvider::HOME);


        return response()->json([
            'response_code' => 200,
            'data' => [
                'user' => [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email
                ],
                'token' => $token,
                'message' => 'Logged in successfully.'
            ]
        ]);
    }

/**
     * @OA\Get(
     *      path="/api/profile",
     *      summary="Profile",
     *      tags={"Admin"},
     *      operationId="viewProfile",
     *      security={{"bearer_security":{}}},
     * @OA\Response(response=200,description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=406,description="not acceptable", @OA\JsonContent()),
     * @OA\Response(response=500,description="internal server error", @OA\JsonContent()),
     *)
     *
     */
    public function viewProfile(Request $request){
        return response()->json([
            'response_code' => 200,
            'message' => 'Current user profile.',
            'errors' => (Object)[],
            'data' => auth()->user()
        ], 200);
    }

     /**
     * @OA\Post(
     *      path="/api/profile",
     *      summary="Profile",
     *      tags={"Admin"},
     *      operationId="profile",
     *      security={{"bearer_security":{}}},
     * @OA\Response(response=200,description="successful operation", @OA\JsonContent()),
     * @OA\Response(response=406,description="not acceptable", @OA\JsonContent()),
     * @OA\Response(response=500,description="internal server error", @OA\JsonContent()),
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
     *                      property="email",
     *                      type="number"
     *                  )
     *             )
     *         )
     *     )
     *)
     *
     */
    public function profile(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response_code' => 422,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
                'data' => (object)[]
            ], 422);
        }


        if (User::where('email','==',$request->email)->where('id','<>',auth()->user()->id)->count() != 0) {
            return response()->json([
                'response_code' => 422,
                'message' => 'The given data was invalid.',
                'errors' => "Email already used",
                'data' => (object)[]
            ], 422);
        }


        $user = User::where('id',auth()->user()->id)->first();

        $user->fill(['name'=>$request->name,'email'=>$request->email]);

        return response()->json([
            'response_code' => 422,
            'message' => 'The given data was invalid.',
            'errors' => [],
            'data' => $user
        ], 422);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }



}
