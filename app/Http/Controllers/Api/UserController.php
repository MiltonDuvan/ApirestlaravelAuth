<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;



class UserController extends Controller
{

    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);


        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = hash::make($request->password);

        $user->save();

        return response()->json([
            "status" => 1,
            "message" => "Registro exitoso.",
        ]);
    }

    public function login(Request $request)
    {

        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        $user = User::where("email", "=", $request->email)->first();

        if (isset($user->id)) {
            if (Hash::check($request->password, $user->password)) {
                //creando token
                $token = $user->createToken("auth_token")->plainTextToken;
                //si todo esta bien creamos una respuesta
                return response()->json([
                    "status" => 1,
                    "message" => "Usuario Logueado Correctamente!",
                    "access_token" => $token
                ]);
            } else {
                return response()->json([
                    "status" => 0,
                    "message" => "password incorret",
                ], 404);
            }
        } else {
            return response()->json([
                "status" => 0,
                "message" => "usuario no registrado",
            ],  404);
        }
    }

    public function userProfile(){
        return response()->json([
            "status" => 0,
            "message" => "acerca del perfil usuario",
            "data"=>auth()->user()
        ]);
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            "status" => 1,
            "message" => "acabas de cerrar sesion, token delete succesful",
        ]);
    }
}
