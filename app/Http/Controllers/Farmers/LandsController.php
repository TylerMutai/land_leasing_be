<?php

namespace App\Http\Controllers\Farmers;

use App\Http\Controllers\Controller;
use App\Http\Resources\LandResource;
use App\Http\Resources\LandImageResource;
use App\Models\Land;
use App\Models\LandImage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LandsController extends Controller
{
    public function uploadImage(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $landModel = Land::where('id', $id);
        if ($landModel->first()) {
            $landImageModel = new LandImage();
            $landImageModel->land_id = $id;

            //Save Product Image to local storage
            $path = "LandsData/";

            $image = $request->file("image");
            $name = "land_image_" . now()->timestamp . "." . $image->extension();
            $image->storeAs($path, $name);

            $landImageModel->image = $path . $name;
            if ($landImageModel->save())
                return new LandImageResource($landImageModel);
        }
        return response()->json(["message" => "Could not upload image"], 500);
    }

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lon' => "required",
            'crops' => "required",
            'name' => "required",
            'price' => "required|integer",
            'description' => "required",
            'size' => "required",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $user = Auth::guard('api')->user();

        $landModel = new Land();
        $landModel->lat = $request->input('lat');
        $landModel->lon = $request->input('lon');
        $landModel->crops = $request->input('crops');
        $landModel->price = $request->input('price');
        $landModel->name = $request->input('name');
        $landModel->description = $request->input('description');
        $landModel->size = $request->input('size');
        $landModel->farmer_id = $user->id;
        if ($landModel->save()) {
            return new LandResource($landModel);
        }
        return response()->json(["message" => "Could not upload land. Try again later."], 500);
    }

    public function update(Request $request, $id)
    {
        $landModel = Land::where('id', $id);

        if ($landModel->first()) {
            $landModel = $landModel->first();
            if ($request->input('lat', 'yes') !== "yes")
                $landModel->access_token = $request->input('lat');
            if ($request->input('lon', 'yes') !== "yes")
                $landModel->calendar_id = $request->input('lon');
            if ($request->input('crops', 'yes') !== "yes")
                $landModel->calendar_id = $request->input('crops');
            if ($request->input('description', 'yes') !== "yes")
                $landModel->calendar_id = $request->input('description');
            if ($request->input('price', 'yes') !== "yes")
                $landModel->calendar_id = $request->input('price');
            if ($request->input('size', 'yes') !== "yes")
                $landModel->calendar_id = $request->input('size');

            if ($landModel->save()) {
                return response()->json(new LandResource($landModel));
            }
        }

        return response()->json(["message" => "Could not update land"], 400);
    }

    public function delete($id)
    {
        $landModel = Land::where('id', $id);
        if ($landModel->first()) {
            if ($landModel->delete()) {
                return response()->json(["message" => "Deleted Successfully"]);
            }
            return response()->json(["message" => "Could not delete. Try again later."], 500);
        }
        return response()->json(["message" => "Does not exist"], 404);
    }

    //Return the image as per given ID (Product ID)
    public function getImage($id)
    {
        if (Land::where('id', $id)->first() == null) abort(404);
        $path = Land::where('id', $id)->first()->image;
        if (!Storage::exists($path)) abort(404);

        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }
}
