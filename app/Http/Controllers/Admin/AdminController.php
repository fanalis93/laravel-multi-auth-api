<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function user(Request $request)
    {
        return response()->json([
            'message' => 'Hello from admin user route!'
        ], 200);
    }
}
