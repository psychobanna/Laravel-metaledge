<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }

     /**
     * @OA\Post(
     *      path="/api/change-password",
     *      summary="Change Passowrd",
     *      tags={"Admin"},
     *      operationId="changepassword",
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
     *                      property="oldpassword",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="newpassword",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="newpassword_confirmation",
     *                      type="string"
     *                  )
     *             )
     *         )
     *     )
     *)
     *
     */
    public function changepassword(Request $request){

        $validator = Validator::make($request->all(), [
            'oldpassword' => 'required',
            'newpassword' => ['required', Rules\Password::defaults(),'confirmed']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response_code' => 422,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
                'data' => (object)[]
            ], 422);
        }

        $user = User::where('id',auth()->user()->id)->first();

        if(!Hash::check($request->oldpassword, $user->password)){
            return response()->json([
                'response_code' => 200,
                'message' => 'Password not Match.',
                'errors' => ['password'=>['Password not Match.']],
                'data' => (Object)[]
            ], 200);
        }

        $user->fill([
            'password' => Hash::make($request->newpassword)
            ])->save();


        return response()->json([
            'response_code' => 200,
            'message' => 'Password updated.',
            'errors' => [],
            'data' => $user
        ], 200);
    }
}
