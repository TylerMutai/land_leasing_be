<?php

namespace App\Http\Controllers\Auth;

use App\Http\ACL\Roles;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
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
<<<<<<< HEAD
            'phone_number' => 'required|unique:users',
=======
            'phone_number' => 'required|unique:users|integer',
>>>>>>> 76fa374a907127ba5d8b9888d4b55d0561107667
            'email' => 'required|email|unique:users',
            'password' => ['required',
                'min:6',
                'confirmed']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $user = User::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $user->assignRole($role);
        event(new Registered($user));
        return response()->json($user, 201);
    }

    public function registerFarmer(Request $request)
    {
        return $this->saveUserToDb($request,Roles::$FARMER);
    }

    public function registerUser(Request $request)
    {
        return $this->saveUserToDb($request,Roles::$USER);
    }

    public function registerMerchant(Request $request)
    {
        return $this->saveUserToDb($request,Roles::$MERCHANT);
    }

}
