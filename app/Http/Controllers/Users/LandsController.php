<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\LandResource;
use App\Models\Land;
use App\Models\LandImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LandsController extends Controller
{
    public function get(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'crop' => 'required',
            'location' => "required",
            'price' => "required|integer",
        ]);

        if ($validator->fails()) {
            return LandResource::collection(Land::all());
        }
        $crops = $request->input('crop', "");
        $location = $request->input('location', "");
        $price = $request->input('price', 0);

        $land = Land::where('crops', "LIKE", "%${crops}%")
            ->where('name', "LIKE", "%${location}%")
            ->where('price', "<=", "${price}");

        if ($land->first())
            return LandResource::collection($land->get());
        return response()->json([]);
    }

    public function getDetail($id)
    {
        if (Land::where('id', $id)->first())
            return new LandResource(Land::find($id));
        return response()->json([], 404);
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
