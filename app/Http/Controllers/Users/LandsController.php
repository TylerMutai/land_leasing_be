<?php

namespace App\Http\Controllers\Users;

use App\Http\Constants\LandStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\LandResource;
use App\Models\Land;
use App\Models\LandImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use SmoDav\Mpesa\Laravel\Facades\STK;

class LandsController extends Controller
{
    public function get(Request $request)
    {
        //This is for searching lands according to specified parameters.
<<<<<<< HEAD
       /* $validator = Validator::make($request->all(), [
=======
        $validator = Validator::make($request->all(), [
>>>>>>> 76fa374a907127ba5d8b9888d4b55d0561107667
            'crop' => 'required',
            'location' => "required",
            'price' => "required|integer",
        ]);

        if ($validator->fails()) {
            return LandResource::collection(Land::where('status', LandStatus::$AVAILABLE)->get());
<<<<<<< HEAD
        } */
        $crops = $request->input('crop', "");
        $location = $request->input('location', "");
        $price = $request->input('price', 0);
       // DB::enableQueryLog();
        $land = Land::where('status', LandStatus::$AVAILABLE);
        
        if(strlen($crops) > 0){
            error_log($crops);
            $land->where('crops', "LIKE", "%${crops}%");
        }
           
        if(strlen($location) > 0){
            error_log($location);
            $land->where('name', "LIKE", "%${location}%");
        }
          
        if($price > 0) {
            error_log($price);
            $land->where('price', "<=", "${price}");
        }
           
         return LandResource::collection($land->get());
=======
        }
        $crops = $request->input('crop', "");
        $location = $request->input('location', "");
        $price = $request->input('price', 0);

        $land = Land::where('crops', "LIKE", "%${crops}%")
            ->where('status', LandStatus::$AVAILABLE)
            ->where('name', "LIKE", "%${location}%")
            ->where('price', "<=", "${price}");

        if ($land->first())
            return LandResource::collection($land->get());
        return response()->json([]);
>>>>>>> 76fa374a907127ba5d8b9888d4b55d0561107667
    }

    public function getDetail($id)
    {
        if (Land::where('id', $id)->first())
            return new LandResource(Land::find($id));
        return response()->json([], 404);
    }

<<<<<<< HEAD
    public function markAsSold(Request $request){
        $validator = Validator::make($request->all(), [
            'land_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(["message" => "The specified land does not exist"], 404);
        }

        $land = Land::find($request->input('land_id'));
        $land->status = LandStatus::$SOLD;
        if ($land->save()) {
            return response()->json(["message" => "Land bought successfully!"]);
        }
    }

=======
>>>>>>> 76fa374a907127ba5d8b9888d4b55d0561107667
    public function buy(Request $request)
    {
        //This is for searching lands according to specified parameters.
        $validator = Validator::make($request->all(), [
            'land_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(["message" => "The specified land does not exist"], 404);
        }

        $land = Land::find($request->input('land_id'));
        if ($land) {
            if ($land->status != LandStatus::$AVAILABLE)
                return response()->json(["message" => "Land already taken."], 405);
<<<<<<< HEAD
                if ($land->price > 75000)
                return response()->json(["message" => "Amounts greater than 75000 cannot be paid via MPesa"], 405);

            $phone = Auth::guard('api')->user()->phone_number;
                $phone = substr($phone, 0, 1) === "0"
            ? "254" . substr($phone, 1)
            : (substr($phone, 0, 3) !== "254"
                ? "254" . $phone
                : $phone);
            
        //TODO: Return $land->price
        $response = STK::request(1)
            ->from($phone)
            ->usingReference('STK300521', 'Land Purchase: ' . $land->name)
            ->setCommand("CustomerPayBillOnline")
            ->push();
        return response()->json($response);
=======
            $land->status = LandStatus::$SOLD;

            $phone = Auth::guard('api')->user()->phone_number;
            if (substr($phone, 0, 1) == "0")
                $phone = "254" . substr($phone, 1);
            if ($land->price > 75000)
                return response()->json(["message" => "Amounts greater than 75000 cannot be paid via MPesa"], 405);
            STK::request($land->price)
                ->from($phone)
                ->usingReference('STK300521', 'Land Purchase: ' . $land->name)
                ->push();
            if ($land->save()) {
                return response()->json(["message" => "Land bought successfully!"]);
            }
>>>>>>> 76fa374a907127ba5d8b9888d4b55d0561107667
        }
        return response()->json(["message" => "not found."], 404);
    }

    //Return the image as per given ID (Land ID)
    public function getImage($id)
    {
        if (LandImage::where('id', $id)->first() == null) abort(404);
        $path = LandImage::where('id', $id)->first()->image;
        if (!Storage::exists($path)) abort(404);

        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }
}
