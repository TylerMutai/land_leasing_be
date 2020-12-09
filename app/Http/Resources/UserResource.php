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
        $role = "";
        if ($this->hasRole(Roles::$FARMER))
            $role = Roles::$FARMER;
        elseif ($this->hasRole(Roles::$MERCHANT))
            $role = Roles::$MERCHANT;
        elseif ($this->hasRole(Roles::$USER))
            $role = Roles::$USER;
        elseif ($this->hasRole(Roles::$ADMIN))
            $role = Roles::$ADMIN;

        return [
            "email" => $this->email,
            "id" => $this->id,
            "name" => $this->name,
            "phone_number" => $this->phone_number,
            "profile_photo_url" => $this->profile_photo_url,
            'role' => $role,
        ];
    }
}
