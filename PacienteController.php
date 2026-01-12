<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PacienteController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $pacientes = Paciente::all();
        } else {
            $pacientes = Paciente::where('user_id', $user->id)->get();
        }

        return view('pacientes.index', compact('pacientes'));
    }

    public function create()
    {
        return view('pacientes.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombres' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required',
            'ocupacion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'motivo_consulta' => 'required|string',
        ]);

        $edad = Carbon::parse($request->fecha_nacimiento)->age;

        Paciente::create([
            'user_id' => auth()->id(),
            'nombres' => $request->nombres,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'edad' => $edad,
            'genero' => $request->genero,
            'ocupacion' => $request->ocupacion,
            'telefono' => $request->telefono,
            'motivo_consulta' => $request->motivo_consulta,
        ]);

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente registrado correctamente');
    }

    public function edit(Paciente $paciente)
    {
        return view('pacientes.form', compact('paciente'));
    }

    public function update(Request $request, Paciente $paciente)
    {
        $request->validate([
            'nombres' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'genero' => 'required',
            'ocupacion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'motivo_consulta' => 'required|string',
        ]);

        $edad = Carbon::parse($request->fecha_nacimiento)->age;

        $paciente->update([
            'nombres' => $request->nombres,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'edad' => $edad,
            'genero' => $request->genero,
            'ocupacion' => $request->ocupacion,
            'telefono' => $request->telefono,
            'motivo_consulta' => $request->motivo_consulta,
        ]);

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente actualizado correctamente');
    }
}
