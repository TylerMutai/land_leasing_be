<?php

namespace App\Http\Controllers\Admin;

use App\Http\Constants\LandStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\LandResource;
use App\Models\Land;

class LandsController extends Controller
{
    public function getBought(){
        return LandResource::collection(Land::where('status', LandStatus::$SOLD)->get());
    }

    public function get(){
        return LandResource::collection(Land::all());
    }
}
