<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $citas = Cita::with('paciente')->get();
        } else {
            $citas = Cita::whereHas('paciente', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->get();
        }

        return view('citas.index', compact('citas'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $pacientes = Paciente::all();
        return view('citas.create', compact('pacientes'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'paciente_id' => 'required',
            'fecha' => 'required',
            'hora' => 'required',
            'motivo' => 'required',
        ]);

        Cita::create($request->all());

        return redirect()->route('citas.index');
    }
}
