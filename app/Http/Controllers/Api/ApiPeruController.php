<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiPeruController extends Controller
{

    public function getDni(String $dni)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://apiperu.dev/api/dni/$dni?api_token=cf97fa0262dd971ad0102e941ead4e05756e5b8dd0705e6446d8ef87844fea07",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYPEER => false
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return null;
        } else {
            return json_decode($response);
        }
    }
}
