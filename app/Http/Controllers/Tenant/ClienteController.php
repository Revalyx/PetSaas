<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    /**
     * Listado de clientes
     */
    public function index()
    {
        $clientes = Cliente::all();
        return view('tenant.clientes.index', compact('clientes'));
    }

    /**
     * Formulario crear cliente
     */
    public function create()
    {
        return view('tenant.clientes.create');
    }

    /**
     * Guardar cliente
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono'  => 'nullable|string|max:255',
            'email'     => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:255',
            'notas'     => 'nullable|string',
        ]);

        Cliente::create($request->all());

        return redirect()
            ->route('tenant.clientes.index')
            ->with('success', 'Cliente creado correctamente.');
    }

    /**
     * Editar cliente
     */
    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('tenant.clientes.edit', compact('cliente'));
    }

    /**
     * Actualizar cliente
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre'    => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono'  => 'nullable|string|max:255',
            'email'     => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:255',
            'notas'     => 'nullable|string',
        ]);

        $cliente = Cliente::findOrFail($id);
        $cliente->update($request->all());

        return redirect()
            ->route('tenant.clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    /**
     * Eliminar cliente
     */
    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()
            ->route('tenant.clientes.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}
