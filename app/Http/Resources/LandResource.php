<?php

namespace App\Http\Resources;

use App\Models\LandImage;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class LandResource extends JsonResource
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
            "lon" => $this->lon,
            "lat" => $this->lat,
            "name" => $this->name,
            "lease_period" => $this->lease_period,
            "description" => $this->description,
            "crops" => $this->crops,
            "price" => $this->price,
            "size" => $this->size,
            "status" => $this->status,
            "image" => LandImageResource::collection(LandImage::where('land_id', $this->id)->get()),
            "farmer" => new UserResource(User::find($this->farmer_id)),
        ];
    }
}
