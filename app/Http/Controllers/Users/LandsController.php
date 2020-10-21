<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\LandResource;
use App\Models\Land;
use App\Models\LandImage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class LandsController extends Controller
{
    public function get()
    {
        return LandResource::collection(Land::all());
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
