<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'remember_me' => 'boolean',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            // TODO: Ignore email verification for now.
            if ($user->email_verified_at == null) {
                $user->email_verified_at = now();
                $user->save();
                /*event(new Registered($user));
                Auth::logout();
                return response()->json(["message" => "You haven't verified your email. " .
                    "The verification email has been resent to your email address"], 401);*/
            }

            if ($user->active == 0) {
                Auth::logout();
                return response()->json(["message" => "Your account has been deactivated. Contact support"], 401);
            }

            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            if ($request->remember_me)
                $token->expires_at = Carbon::now()->addMonth();
            $token->save();
            return response()->json([
                'message' => 'Authorization Granted',
                'access_token' => $tokenResult->accessToken,
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ]);
        }
        return response()->json(["message" => "Unauthorised: Wrong credentials were provided."], 401);
    }
}
