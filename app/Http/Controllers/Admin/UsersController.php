<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function get()
    {
        return UserResource::collection(User::all());
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        if ($user->active === 0) {
            $user->active = 1;
        } else {
            $user->active = 0;
        }
        if ($user->save()) {
            return response()->json(["message" => "User deleted successfully"]);
        }
        return response()->json(["message" => "Could not delete user"], 500);
    }
}
