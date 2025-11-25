<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Mascota;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /** LISTADO */
    public function index()
    {
        $appointments = Appointment::with(['customer', 'pet'])
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('tenant.appointments.index', compact('appointments'));
    }

    /** FORMULARIO DE CREAR */
    public function create(Request $request)
{
    $customers = Cliente::orderBy('nombre')->get();
    $pets = Mascota::orderBy('nombre')->get();
    $prefillDate = $request->query('date', null); // YYYY-MM-DD or null

    return view('tenant.appointments.create', compact('customers', 'pets', 'prefillDate'));
}


    /** GUARDAR */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|integer',
            'date'        => 'required|date',
            'start_time'  => 'required',
            'end_time'    => 'nullable',
            'status'      => 'required',
        ]);

        Appointment::create([
            'customer_id' => $request->customer_id,
            'pet_id'      => $request->pet_id ?: null,
            'date'        => $request->date,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time ?: null,
            'notes'       => $request->notes,
            'status'      => $request->status,
        ]);

        return redirect()->route('tenant.appointments.index');
    }

    /** FORMULARIO DE EDITAR */
    public function edit($id)
    {
        $appointment = Appointment::with(['customer', 'pet'])->findOrFail($id);

        $customers = Cliente::orderBy('nombre')->get();
        $pets      = Mascota::orderBy('nombre')->get();

        return view('tenant.appointments.edit', compact('appointment', 'customers', 'pets'));
    }

    /** ACTUALIZAR */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $request->validate([
            'customer_id' => 'required',
            'date'        => 'required',
            'start_time'  => 'required',
            'status'      => 'required'
        ]);

        $appointment->update([
            'customer_id' => $request->customer_id,
            'pet_id'      => $request->pet_id ?: null,
            'date'        => $request->date,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time ?: null,
            'notes'       => $request->notes,
            'status'      => $request->status,
        ]);

        return redirect()->route('tenant.appointments.index');
    }

    /** ELIMINAR */
    public function destroy($id)
    {
        Appointment::findOrFail($id)->delete();
        return redirect()->route('tenant.appointments.index');
    }
}
