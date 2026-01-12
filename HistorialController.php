<?php

namespace App\Http\Controllers;

use App\Models\Historial;
use App\Models\Paciente;
use Illuminate\Http\Request;

class HistorialController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $historiales = Historial::with('paciente')->get();
        } else {
            $historiales = Historial::whereHas('paciente', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->get();
        }

        return view('historiales.index', compact('historiales'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $pacientes = Paciente::all();
        return view('historiales.create', compact('pacientes'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'paciente_id' => 'required',
            'diagnostico' => 'required',
            'tratamiento' => 'required',
        ]);

        Historial::create($request->all());

        return redirect()->route('historiales.index');
    }
}
