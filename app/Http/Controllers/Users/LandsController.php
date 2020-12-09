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
       /* $validator = Validator::make($request->all(), [
            'crop' => 'required',
            'location' => "required",
            'price' => "required|integer",
        ]);

        if ($validator->fails()) {
            return LandResource::collection(Land::where('status', LandStatus::$AVAILABLE)->get());
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
    }

    public function getDetail($id)
    {
        if (Land::where('id', $id)->first())
            return new LandResource(Land::find($id));
        return response()->json([], 404);
    }

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
