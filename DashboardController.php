<?php

namespace App\Http\Controllers;

use App\Models\Paciente;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ADMIN: ve todo
        if ($user->role === 'admin') {
            $totalPacientes = Paciente::count();

            return view('dashboard.admin', compact('totalPacientes'));
        }

        // PACIENTE: solo lo suyo
        $pacientes = Paciente::where('user_id', $user->id)->get();

        return view('dashboard.paciente', compact('pacientes'));
    }
}
