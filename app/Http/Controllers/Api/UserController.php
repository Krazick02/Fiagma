<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\AppHelper as Helper;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => "Error al obtener registro",
                'code' => -2,
                'data' => null,
                'errors' => [
                    "El usuario no existe"
                ]
            ], 404);
        }

        $user->token = $user->createToken('session-token')->plainTextToken;

        return response()->json([
            'message' => "Registro obtenido correctamente",
            'code' => 2,
            'data' => $user,
            'errors' => null
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => "Sesión finalizada correctamente",
            'code' => 2,
            'data' => null,
            'errors' => null
        ], 200);
    }
    public function index()
    {
        $users = User::all();

        if ($users) {
            return response()->json([
                'message' => "Registro consultado exitosamente",
                'code' => 2,
                'data' => $users
            ], 200);

        }

        return response()->json([
            'message' => "Error en el servidor",
            'code' => -2,
            'data' => null
        ], 404);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (isset($request->password)) {
            $request['password'] = bcrypt($request->password);
        }

        $user = User::create($request->all());

        if ($user) {
            //subir foto
            if ($request->hasFile('profile_photo')) {
                $name_file = Helper::store_file($request, $request->file('profile_photo'), $user, "users/avatars", null);
                $user->profile_photo_path = $name_file;
                $user->save();
            }


            return response()->json([
                'message' => "Registro agregado exitosamente",
                'code' => 2,
                'data' => $user
            ], 200);

        }

        return response()->json([
            'message' => "Error en el servidor",
            'code' => -2,
            'data' => null
        ], 404);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::where('id', $id)
            ->first();

        if ($user) {

            return response()->json([
                'message' => "Registro consultado exitosamente",
                'code' => 2,
                'data' => $user
            ], 200);

        }

        return response()->json([
            'message' => "Error en el servidor",
            'code' => -2,
            'data' => null
        ], 404);
    }

    public function get($id)
    {
        $user = User::where('id', $id)
            ->first();

        if ($user) {

            return response()->json([
                'message' => "Registro consultado correctamente",
                'code' => 4,
                'data' => $user
            ], 200);
        }


        return response()->json([
            'message' => "Error en el servidor",
            'code' => 5,
            'data' => null
        ], 404);
    }
    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = User::where('id',$request['id'])
                ->get();

        if($user){
            if ($user->update($request->all())) {
                //subir nuevo archivo
                if ($request->hasFile('profile_photo')) {
                    //eliminar archivo anterior
                    $path = Helper::delete_file($user, "profile_photo_path", "users/avatars");
    
                    if ($path) {
                        $user->profile_photo_path = null;
                    }
    
                    //new avatar
                    $name_file = Helper::store_file($request, $request->file('profile_photo'), $user, "users/avatars", null);
                    $user->profile_photo_path = $name_file;
                    $user->save();
                }
    
                return response()->json([
                    'message' => "Registro editado exitosamente",
                    'code' => 2,
                    'data' => $user
                ], 200);
    
            }
        }

        return response()->json([
            'message' => "Error en el servidor",
            'code' => -2,
            'data' => null
        ], 404);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario = User::find($id);

        if ($usuario) {
            if ($usuario->delete()) {

                $path = Helper::delete_file($usuario, "profile_photo_path", "users/avatars");

                if ($path) {
                    $usuario->profile_photo_path = null;
                }

                return response()->json([
                    'message' => "Registro Eliminado correctamente",
                    'code' => 2,
                    'data' => null
                ], 200);
            }
        }

        return response()->json([
            'message' => "No se ha podido eliminar",
            'code' => -2,
            'data' => null
        ], 200);

    }

}