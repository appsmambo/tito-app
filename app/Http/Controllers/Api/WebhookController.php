<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\SessionController;
use Illuminate\Http\Request;

class WebhookController extends Controller
{

    public function index()
    {
        SessionController::store();
        //$products = Product::all();
        return response()->json([
            "success" => true,
            "message" => "WebHookController",
            "data" => []
        ]);

    }
}
