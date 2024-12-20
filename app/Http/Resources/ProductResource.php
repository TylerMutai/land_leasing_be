<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "price" => $this->price,
            "organization_name" => $this->organization_name,
            "description" => $this->description,
            "image" => url('api/products/image/' . $this->id),
            "merchant" => new UserResource(User::find($this->merchant_id)),
        ];
    }
}
