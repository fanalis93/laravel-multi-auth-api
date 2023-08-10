<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function user(Request $request)
    {
        return response()->json([
            'message' => 'Hello from student user route!'
        ], 200);
    }
}
