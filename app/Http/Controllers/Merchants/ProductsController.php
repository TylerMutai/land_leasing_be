<?php

namespace App\Http\Controllers\Merchants;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    public function get()
    {
        return ProductResource::collection(Product::where('merchant_id', Auth::guard('api')->user()->id)->get());
    }

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'organization_name' => "required",
            'price' => "required|integer",
            'description' => "required",
            'image' => 'required|image|max:5120'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $user = Auth::guard('api')->user();

        //Save Product Image to local storage
        $path = "ProductsData/";

        $image = $request->file("image");
        $name = "product_image_" . now()->timestamp . "." . $image->extension();
        $image->storeAs($path, $name);

        $productModel = new Product();
        $productModel->name = $request->input('name');
        $productModel->organization_name = $request->input('organization_name');
        $productModel->price = $request->input('price');
        $productModel->description = $request->input('description');
        $productModel->image = $path . $name;
        $productModel->merchant_id = $user->id;
        if ($productModel->save()) {
            return new ProductResource($productModel);
        }
        return response()->json(["message" => "Could not upload land. Try again later."], 500);
    }

    public function update(Request $request, $id)
    {
        $productModel = Product::where('id', $id);

        if ($productModel->first()) {
            $productModel = $productModel->first();
            if ($request->input('name', 'yes') !== "yes")
                $productModel->access_token = $request->input('name');
            if ($request->input('organization_name', 'yes') !== "yes")
                $productModel->calendar_id = $request->input('organization_name');
            if ($request->input('description', 'yes') !== "yes")
                $productModel->calendar_id = $request->input('description');
            if ($request->input('price', 'yes') !== "yes")
                $productModel->calendar_id = $request->input('price');

            if ($productModel->save()) {
                return response()->json(new ProductResource($productModel));
            }

        }

        return response()->json(["message" => "Could not update product"], 400);
    }

    public function delete($id)
    {
        $productModel = Product::where('id', $id);
        if ($productModel->first()) {
            if ($productModel->delete()) {
                return response()->json(["message" => "Deleted Successfully"]);
            }
            return response()->json(["message" => "Could not delete. Try again later."], 500);
        }
        return response()->json(["message" => "Does not exist"], 404);
    }
}
