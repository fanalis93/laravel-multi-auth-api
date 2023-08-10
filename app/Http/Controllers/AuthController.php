<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function test()
    {
        return response()->json([
            'message' => 'Hello World!'
        ], 200);
    }

    public function login(Request $request)
    {
        // dd($request->all());

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response([
                    'message' => ['These credentials do not match our records.']
                ], 404);
            }


            $role = $user->role; // Assuming the role field is named 'role'

            $tokenName = 'auth-token';

            // Customize the token name and redirect routes based on the user's role
            switch ($role) {
                case 1: // User
                    $tokenName = 'student-token';
                    $redirectRoute = 'student/dashboard';
                    break;
                case 2: // Client
                    $tokenName = 'client-token';
                    $redirectRoute = 'client/dashboard';
                    break;
                case 3: // Admin
                    $tokenName = 'admin-token';
                    $redirectRoute = 'admin/dashboard';
                    break;
                default:
                    $redirectRoute = 'login'; // Default redirect route for unknown roles
            }

            $request->session()->regenerate();
            $token = $user->createToken($tokenName)->plainTextToken;
            $response = [
                'user' => $user,
                'token' => $token,
                'redirect_route' => $redirectRoute,
            ];

            return response($response, 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function logout(Request $request)
    {


        try {
            // auth user logout-
            // dd($request->all());
            $request->session()->invalidate();

            $request->session()->regenerateToken();

            // remove all user tokens
            $request->user()->tokens()->delete();

            // clear all cache data
            Cache::flush();

            return response()->json([
                'message' => 'Logged out'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }


    public function register(Request $request)
    {
        // dd($request->all());
        try {
            $rules = [
                'name' => 'required|unique:users',
                'password' => 'required',
                'email' => 'required|unique:users,email',
                'role' => 'nullable|in:1,2', // Make sure role is 1 or 2
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $data = $validator->validated();

            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->role = $data['role'] ?? 1;
            $user->password = Hash::make($data['password']);

            // $token = $user->createToken('auth-token')->plainTextToken;

            if ($user->save()) {
                // Auth::login($user);
                if ($user->role == 1) {
                    $user->student()->create([
                        'user_id' => $user->id,
                        'username' => $user->name,
                        'email' => $user->email,
                        'password' => $user->password,
                    ]);
                } else if ($user->role == 2) {
                    $user->client()->create([
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'password' => $user->password,
                    ]);
                }

                // check if user is logged in
                if (Auth::check()) {
                    // redirect to home page
                    $log = 'user logged in';
                }
                $response = [
                    'user' => $user,
                    'log' => $log ?? 'user not logged in',
                    // 'token' => $token,
                ];

                return response($response, 201);
            } else {
                return response()->json([
                    'message' => 'Something went wrong'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function user(Request $request, $id)
    {
        //        $user = Auth::user();
        //        dd($request->all());
        $user = User::find($id);
        return response()->json([
            'user' => $user
        ], 200);
    }
}
