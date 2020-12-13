<?php

namespace App\Http\Resources;

use App\Models\LandImage;
use App\Models\User;
use App\Models\UserLand;
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
        $user = UserLand::where('land_id', $this->id);
        $user_id = 0;
        if ($user->first())
            $user_id = $user->first()->user_id;
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
            "created" => $this->created_at,
            "image" => LandImageResource::collection(LandImage::where('land_id', $this->id)->get()),
            "farmer" => new UserResource(User::find($this->farmer_id)),
            "user" => new UserResource(User::find($user_id)),
            "active" => $this->active,
        ];
    }
}
