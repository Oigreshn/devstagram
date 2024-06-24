<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index() 
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        //Modifica el Resquest para que el UNIQUE funcione al momento de la validacion
        $request->request->add(['username' => Str::slug( $request->username)]);

        //Validacion
        $this->validate($request,[
            'name' => 'required|max:50',
            'username' => 'required|unique:users|min:3|max:20',
            'email' => 'required|unique:users|email|max:80',
            'password' => 'required|confirmed|min:6'
        ]);
        
        User::create(
            [
                'name' => $request->name,
                'username' => $request->username,
                'email'=> $request->email,
                'password' => $request->password,
            ]
        );

        //Autenticar
        //auth()->attempt([
        //    'email' => $request->email,
        //    'password' => $request->password
        //]);
        
        //Otra forma de Autenticar
        auth()->attempt($request->only('email','password'));
        
        //Redireccionar
        //return redirect()->route('posts.index'); 
        return redirect()->route('posts.index', auth()->user()->username);
    }
}
