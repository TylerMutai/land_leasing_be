<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BlogsController extends Controller
{
    public function get()
    {
        return BlogResource::collection(Blog::all());
    }

    public function getDetail($id)
    {
        return new BlogResource(Blog::find($id));
    }

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'body' => "required",
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $user = Auth::guard('api')->user();

        $blogModel = new Blog();
        $blogModel->title = $request->input('title');
        $blogModel->body = $request->input('body');
        $blogModel->uploaded_by = $user->id;
        if ($blogModel->save()) {
            return new BlogResource($blogModel);
        }
        return response()->json(["message" => "Could not upload blog. Try again later."], 500);
    }

    public function update(Request $request, $id)
    {
        $blogModel = Blog::where('id', $id);

        if ($blogModel->first()) {
            $blogModel = $blogModel->first();
            if ($request->input('title', 'yes') !== "yes")
                $blogModel->title = $request->input('title');
            if ($request->input('body', 'yes') !== "yes")
                $blogModel->body = $request->input('body');
            if ($blogModel->save()) {
                return response()->json(new BlogResource($blogModel));
            }
        }
        return response()->json(["message" => "Could not update blog"], 500);
    }

    public function delete($id)
    {
        $landModel = Blog::where('id', $id);
        if ($landModel->first()) {
            if ($landModel->delete()) {
                return response()->json(["message" => "Deleted Successfully"]);
            }
            return response()->json(["message" => "Could not delete. Try again later."], 500);
        }
        return response()->json(["message" => "Does not exist"], 404);
    }
}
