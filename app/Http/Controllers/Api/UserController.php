<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function Register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|confirmed|min:3',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'message' => 'Registro exitoso',
            'user' => $user
        ], 201);
    }

    public function LogIn(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (isset($user->id)){
            if (Hash::check($request->password, $user->password)){
                $token = $user->createToken('auth_token')->accessToken;
                return response()->json([
                    'message' => 'Login exitoso',
                    'token' => $token
                ], 200);
            } 
            else {
                return response()->json([
                    'message' => 'Contraseña incorrecta'
                ], 401);
            };
    }
        else {
            return response()->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        };
    
}

    public function UserProfile()
    {
        return response()->json([
            'message' => 'Perfil de usuario',
            'user' => auth()->user()
        ], 200);
    }

    public function LogOut()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json([
            'message' => 'Logout exitoso'
        ], 200);
    }

}
