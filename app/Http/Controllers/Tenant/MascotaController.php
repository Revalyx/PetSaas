<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Mascota;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class MascotaController extends Controller
{
    public function index()
    {
        $mascotas = Mascota::with('cliente')->get();
        return view('mascotas.index', compact('mascotas'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        return view('mascotas.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
    'cliente_id' => [
        'required',
        Rule::exists('tenant.clientes', 'id')
    ],
    'nombre' => 'required|string|max:255',
]);

        Mascota::create($request->all());

        return redirect()
            ->route('tenant.mascotas.index')
            ->with('ok', 'Mascota creada correctamente');
    }

    public function edit($id)
    {
        $mascota  = Mascota::findOrFail($id);
        $clientes = Cliente::all();

        return view('mascotas.edit', compact('mascota', 'clientes'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'cliente_id' => 'required|exists:clientes,id',
        'nombre'     => 'required|string|max:255',
        'raza'       => 'nullable|string|max:255',
        'edad'       => 'nullable|integer',
        'notas'      => 'nullable|string',
    ]);

    $mascota = Mascota::findOrFail($id);
    $mascota->update($request->all());

    return redirect()
            ->route('tenant.mascotas.index')
            ->with('ok', 'Mascota actualizada correctamente');
}

}
