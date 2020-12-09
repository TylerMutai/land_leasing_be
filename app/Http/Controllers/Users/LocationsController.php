<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class LocationsController extends Controller
{
    public function search(Request $request)
    {
        $searchQuery = $request->input('search_query');
        $client = new Client([
            'timeout' => 10
        ]);

        try {
            $response = $client->post("https://maps.googleapis.com/maps/api/place/textsearch/json", [
                'query' => [
                    "fields" => "formatted_address,name,geometry,place_id",
                    "key" => "AIzaSyD-Sgkl8i39IseExgaEdIwWigfqm0OpfAs",
                    "query" => $searchQuery,
                    "location" => "-1.28333,36.81667", //Nairobi's longitude and latitude.
                    "radius" => "50000",
                    "inputtype" => "textquery"
                ],
            ]);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return response()->json(['message' => 'Unauthorised. Wrong username or password'], 401);
        }
        if ($response->getStatusCode() == 200) {
            return $response->getBody();
        } else {
<<<<<<< HEAD
	    error_log($response->getBody());
=======
>>>>>>> 76fa374a907127ba5d8b9888d4b55d0561107667
            return response()->json(['message' => 'Unauthorised. Wrong username or password'], 401);
        }

    }
}
