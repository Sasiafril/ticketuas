<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use Validator;
use App\Models\User;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user'
         ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['data' => $user,
            'access_token' => $token, 
            'token_type' => 'Bearer', ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password')))
        {
            return response()
                ->json(['message' => 'Login gagal'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        if($user->role !== 'user'){
            return response()
            ->json(['message' => 'Anda buka user']);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['message' => 'Hi '.$user->name.' ','access_token' => $token, 'token_type' => 'Bearer', ]);
    }

    public function loginAdmin(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password')))
        {
            return response()
                ->json(['message' => 'Login gagal'], 401);
        }

        $admin = User::where('email', $request['email'])->firstOrFail();

        if($admin->role !== 'admin'){
            return response()
            ->json(['message' => 'Anda buka admin']);
        }

        $token = $admin->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['message' => 'Hi '.$admin->name.' ','access_token' => $token, 'token_type' => 'Bearer', ]);
    }

    public function loginSuperAdmin(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password')))
        {
            return response()
                ->json(['message' => 'Login gagal'], 401);
        }

        $admin = User::where('email', $request['email'])->firstOrFail();

        if($admin->role !== 'superadmin'){
            return response()
            ->json(['message' => 'Anda bukan superadmin']);
        }

        $token = $admin->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['message' => 'Hi '.$admin->name.' ','access_token' => $token, 'token_type' => 'Bearer', ]);
    }

    public function updateRole(Request $request){
        $userLogin = auth()->user();
        if($userLogin['role'] !== 'superadmin'){
            return response()->json([
                'status' => 404,
                'message' => "Anda bukan superadmin"
            ], 404);
        }
        $admin = User::where('email', $request['email'])->first();
        if(!$admin){
            return response()->json([
                'status' => 404,
                'message' => "Email not found"
            ], 404);
        }

        $admin->role = $request->input('role');
        $admin->save();

        return response()->json([
            'status' => 201, 
            'data' => $admin,
            'message' => "Success update data"
        ], 201);
    }

    // method for user logout and delete token
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Success Logout'
        ];
    }
}