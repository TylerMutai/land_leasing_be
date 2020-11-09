<?php

namespace App\Http\Resources;

use App\Http\ACL\Roles;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            "email" => $this->email,
            "id" => $this->id,
            "name" => $this->name,
            "phone_number" => $this->phone_number,
            "profile_photo_url" => $this->profile_photo_url,
            'role' => $this->hasRole(Roles::$FARMER) ?
                Roles::$FARMER : $this->hasRole(Roles::$MERCHANT) ?
                    Roles::$MERCHANT : $this->hasRole(Roles::$USER) ? Roles::$USER : Roles::$ADMIN,
        ];
    }
}
