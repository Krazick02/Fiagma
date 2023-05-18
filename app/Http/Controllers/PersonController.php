<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    public function create(){
        return view('fiagma.register');
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'username' => 'required|unique:people|min:4',
            'email' => 'required|email|unique:people',
            'password' => 'required|min:8',
        ]);

        $person = new Person();
        $person->username = $validatedData['username'];
        $person->email = $validatedData['email'];
        $person->password = bcrypt($validatedData['password']); 
        if($person->save()){

            dd($person);
            // return redirect()->route('login')->with('success', 'Persona creada exitosamente');
        }
        // return redirect()->route('login')->with('error', 'Persona no creada');

        
    }
}
