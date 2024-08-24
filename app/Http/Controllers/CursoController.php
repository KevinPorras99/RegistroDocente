<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use Illuminate\Support\Facades\Auth;

class CursoController extends Controller
{
    public function index()
    {
        // Obtener todos los cursos desde la base de datos
        $cursos = Curso::all();
        $user = Auth::user(); // Obtener el usuario autenticado
        return view('cursos', compact('user', 'cursos'));
    }

    public function create()
    {
        return view('cursos.create');
    }

    public function store(Request $request)
    {
        // Validar y crear el curso
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        Curso::create([
            'nombre' => $request->input('nombre'),
        ]);

        return redirect()->route('cursos');
    }
}

