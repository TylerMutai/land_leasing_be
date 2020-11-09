<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function loggedInUser()
    {
        return new UserResource(User::find(Auth::guard('api')->user()->id));
    }

    public function updateUser(Request $request)
    {
        $user = Auth::guard('api')->user();

        if ($request->input('first_name', "yes") != yes)
            $user->first_name = $request->input('first_name');

        if ($request->input('last_name', "yes") != yes)
            $user->last_name = $request->input('last_name');

        if ($request->input('email', "yes") != yes)
            $user->email = $request->input('email');

        if ($user->save()) return new UserResource(User::find(Auth::guard('api')->user()->id));

        return response()->json(["message" => "Could not update user"], 500);
    }
}
