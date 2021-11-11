<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    /**
     * @OA\Post(
     * path="/api/forgot-password",
     * summary="Forgot Password",
     * description="Forgot Password by email",
     * operationId="store",
     * tags={"Admin"},
     * @OA\RequestBody(
     *    required=true,
     *    description="User Email",
     *    @OA\JsonContent(
     *       required={"email"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address. Please try again")
     *        )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        if(User::where('email',$request->email)->count() != 1){

            return response()->json([
                'response_code' => 422,
                'data' => [
                    'message' => 'Email not exist.'
                ]
            ]);
        }

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if($status == Password::RESET_LINK_SENT ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)])){

            return response()->json([
                'response_code' => 200,
                'data' => [
                    'message' => 'Email sent'
                ]
            ]);
        }else{

            return response()->json([
                'response_code' => 422,
                'data' => [
                    'message' => 'Email not sent'
                ]
            ]);
        }
    }
}
