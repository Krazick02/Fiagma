<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Helpers\AppHelper as Helper;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $users = User::all();

        if ($users) {
            return view('admin.users.index', compact('users'));
        }

        return redirect()->back()->with('errors', 'error en el servidor');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store2(Request $request)
    {
        if(isset($request->password)){
            $request['password'] = bcrypt($request->password);
        }

        dd($request);
        // $user = User::create($request->all());

        // if($user){
        //     // //subir foto
        //     // if($request->hasFile('profile_photo')){
        //     //     $name_file = Helper::store_file($request,$request->file('avatar_file'),$user,"users/avatars",null);
        //     //     $user->profile_photo_path = $name_file;
        //     //     $user->save();
        //     // }

        //     dd($user);
        //     // return view('login');
        // }

        // dd('hola bastardo');
        // // return redirect()->back()->with('errors','error servidor');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::where('id', $id)
            ->first();

        if ($user) {

            #return $user;
            return view('admin.users.details', compact('user'));

        }


        return redirect()->back()->with('errors', 'No permitido');
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
        $user = User::find($request->id);

        if(isset($request['password']) && $request['password']!=''){
            $request['password'] = bcrypt($request['password']);
        }else{
            $request['password'] = $user->password;
        }

        if($user->update($request->all())){
            //subir nuevo archivo
            // if($request->hasFile('profile_photo')){
            //     //eliminar archivo anterior
            //     $path = Helper::delete_file($user,"profile_photo_path","users/avatars");

            //     if($path){
            //         $user->profile_photo_path = null;
            //     }

            //     //new avatar
            //     $name_file = Helper::store_file($request,$request->file('profile_photo'),$user,"users/avatars",null);
            //     $user->profile_photo_path = $name_file;
            //     $user->save();
            // }



            return redirect()->back()->with('success', 'Usuario Actualizado.');

        }


        return redirect()->back()->with('errors','error servidor');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario = User::find($id);

        if ($usuario) {
            if ($usuario->delete()) {

                // $path = Helper::delete_file($usuario,"profile_photo_path","users/avatars");

                // if($path){
                //     $usuario->profile_photo_path = null;
                // }


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