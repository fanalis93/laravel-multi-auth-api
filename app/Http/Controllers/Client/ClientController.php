<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function user(Request $request)
    {
        return response()->json([
            'message' => 'Hello from client user route!'
        ], 200);
    }
}
