<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
    * Create new user 
    */
    
    public function register(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|min:2",
            "email" => "required|email|unique:users",
            "password" => "required|min:6"
        ]);

        $data = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        return response()->json([
            "success" => true,
            "message" => "Created successfully",
        ]);
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function login(Request $request)
    {
        $validated = $request->validate([
            "email" => 'required|email',
            "password" => 'required|min:6'
        ]);

        if (! $token = auth()->attempt($validated)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
    /**
     * Update user data
     * 
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            "id" => "required",
            "name" => "required|min:2",
            "email" => ["required","email",Rule::unique("users")->ignore($request->id, 'id')],
        ]);
        $userID = $request->id;
        $user = User::find($userID);
        if($request->newPassword || $request->oldPassword){
            $request->validate([
                "oldPassword" => "required|min:6",
                "newPassword" => "required|different:oldPassword"
            ]);
            if(Hash::check($request->oldPassword, $user->password)){
                $user->fill([
                    "name" => $request->name,
                    "email" => $request->email,
                    "password" => Hash::make($request->newPassword)
                ]);
                $user->save();
                return response()->json([
                    "success" => true,
                    "message" => "Data updated and password changed!"
                ]);
            }else{
                return response()->json([
                    "success" => false,
                    "message" => "Please fill old password and new password corectly!"
                ]);
            }
        }else{
            $user->fill([
                "name" => $request->name,
                "email" => $request->email,
            ]);
            $user->save();
            return response()->json([
                "success" => true,
                "message" => "Data updated"
            ]);
        }
    }


    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
     protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}