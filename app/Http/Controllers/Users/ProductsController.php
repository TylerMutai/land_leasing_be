<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function get()
    {
        return ProductResource::collection(Product::all());
    }

    public function getDetail($id)
    {
        if (Product::where('id', $id)->first())
            return new ProductResource(Product::find($id));
        return response()->json([], 404);
    }

    //Return the image as per given ID (Product ID)
    public function getImage($id)
    {
        if (Product::where('id', $id)->first() == null) abort(404);
        $path = Product::where('id', $id)->first()->image;
        if (!Storage::exists($path)) abort(404);

        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }
}
