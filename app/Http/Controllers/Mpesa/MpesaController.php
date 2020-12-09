<?php

namespace App\Http\Controllers\Mpesa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
<<<<<<< HEAD
use App\Http\Controllers\Users\LandsController;
=======
>>>>>>> 76fa374a907127ba5d8b9888d4b55d0561107667

//use SmoDav\Mpesa\Laravel\Facades\Registrar;
use SmoDav\Mpesa\Laravel\Facades\STK;

class MpesaController extends Controller
{
    public function stkPush(Request $request)
    {
        /*$conf = 'http://example.com/mpesa/confirm?secret=some_secret_hash_key';
        $val = 'http://example.com/mpesa/validate?secret=some_secret_hash_key';

        //Register URL Callbacks??
         $response = Registrar::register(600000)
             ->onConfirmation($conf)
             ->onValidation($val)
             ->submit();*/

        $validator = Validator::make($request->all(), [
            'phone_no' => 'required',
            'amount' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $response = STK::request($request->input('amount'))
            ->from($request->input('phone_no'))
            ->usingReference('STK300521', 'Test Payment')
            ->push();

        return response()->json($response);
    }

    public function confirm()
    {
        return response()->json();
    }

<<<<<<< HEAD
    public function validateMpesaSTKPush(Request $request)
    {
        $response = STK::validate($request->input('request_id', 'wagwan'));
        $landsController = new LandsController();
        if (property_exists($response, "ResultCode")) {
            if ($response->ResultCode === "0") {
                //Successful payment. Let's changed the order to complete.
                return $landsController->markAsSold($request);
            }
            return response()->json(["message" => $response->ResultDesc], 400);
        }
        return response()->json(["message" => "It looks like you pressed confirm before first completing the prompt. Please try again."], 400);
=======
    public function validateMpesa()
    {
        return response()->json();
>>>>>>> 76fa374a907127ba5d8b9888d4b55d0561107667
    }
}
