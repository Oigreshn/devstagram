<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    public function store(Request $request, User $user, Post $post)
    {
        //Validar la Informacion
        $this->validate($request, [
            'comentario' => 'required|max:255'
        ]);

        //Almacenar el Resultado
        Comentario::create([
              'user_id' => auth()->user()->id,
              'post_id' => $post->id,
              'comentario' => $request->comentario
        ]);

        //Imprimir un Mensaje
        return back()->with('mensaje', 'Comentario Realizado con Exito');
    }
}
