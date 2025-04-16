<?php

namespace App\Http\Controllers\Auth;

use App\Http\ACL\Roles;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    public function saveUserToDb(Request $request, $role)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone_number' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => ['required',
                'min:6',
                'confirmed']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $userData = [
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'active' => 1,
            'email_verified_at' => now(),
            'password' => Hash::make($request->password)
        ];

        $user = User::create($userData);

        $user->assignRole($role);
//        event(new Registered($user));
        return response()->json($user, 201);
    }

    public function registerFarmer(Request $request)
    {
        return $this->saveUserToDb($request, Roles::$LESSOR);
    }

    public function registerUser(Request $request)
    {
        return $this->saveUserToDb($request, Roles::$USER);
    }

    public function registerMerchant(Request $request)
    {
        return $this->saveUserToDb($request, Roles::$MERCHANT);
    }

}
