<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PerfilController extends Controller
{
    public function index()
    {
        return view('perfil.index');
    }

    public function store(Request $request)
    {
        //Modifica el Resquest para que el UNIQUE funcione al momento de la validacion
        $request->request->add(['username' => Str::slug( $request->username)]);

        $this->validate($request, [
            'username' => ['required', 'unique:users,username,'. auth()->user()->id, 'min:3', 'max:20',
            'not_in:twitter,editarperfil'],
        ]);

        if($request->imagen){
            $imagen = $request->file('imagen'); 

            $nombreImagen = Str::uuid() . "." . $imagen->extension();
            
            $imagenServidor = Image::make($imagen);
            $imagenServidor->fit(1000,1000);

            $imagenPath = public_path('perfiles') . '/' . $nombreImagen;
            $imagenServidor->save($imagenPath);
        }

        //Guardando Cambios
        $usuario = User::find(auth()->user()->id);
        $usuario->username= $request->username;
        $usuario->imagen = $nombreImagen ?? auth()->user()->imagen ?? '';
        $usuario->save();

        //Redireccionar el Usuario
        return redirect()->route('posts.index',$usuario->username);
    }
}
