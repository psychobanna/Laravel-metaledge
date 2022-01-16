<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

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
     * operationId="storeNewPassword",
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
    public function storeNewPassword(Request $request)
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

        $user = User::where('email', $request->email)->first();

        $password = Str::random(10);


        $userPassword = Hash::make($password);

        $data = User::where('email', $request->email)->update(["password"=>$userPassword]);

        $details = ["title"=>"Forgot Password","body"=>"New Password: ".$password];
        if($data){
            Mail::to($request->email)->send(new EmailTemplate($details));

            if (Mail::failures()) {
                return response()->json([
                    'response_code' => 422,
                    'data' => [
                        'message' => 'Email not sent'
                    ]
                ]);
            }else{

                return response()->json([
                    'response_code' => 200,
                    'data' => [
                        'message' => 'Email sent'
                    ],
                    "Error"=>$request->all()
                ]);

            }
        }else{

            return response()->json([
                'response_code' => 500,
                'data' => [
                    'message' => 'Email not Updated'
                ],
                "Error"=>$request->all()
            ]);
        }


    }
}
