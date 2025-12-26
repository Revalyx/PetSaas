<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Mascota;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Tenant;


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

    /** FORMULARIO CREAR */
    public function create(Request $request)
    {
        $customers = Cliente::orderBy('nombre')->get();
        $pets = Mascota::orderBy('nombre')->get();
        $prefillDate = $request->query('date', null);

        return view('tenant.appointments.create',
            compact('customers', 'pets', 'prefillDate')
        );
    }
    public function petsByClient($clientId)
{
    $tenant = $this->getTenant();

    $pets = Mascota::where('cliente_id', $clientId)
        ->orderBy('nombre')
        ->get(['id', 'nombre']);

    return response()->json($pets);
}
private function getTenant()
{
    $user = auth()->user();

    if (!$user || !$user->tenant_id) {
        abort(403, 'Usuario sin tenant');
    }

    return Tenant::findOrFail($user->tenant_id);
}


    /** GUARDAR */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'date'        => 'required|date',
            'start_time'  => 'required',
            'type'        => 'required',
            'status'      => 'required'
        ]);

        Appointment::create([
            'customer_id' => $request->customer_id,
            'pet_id'      => $request->pet_id ?: null,
            'date'        => $request->date,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time ?: null,
            'notes'       => $request->notes,
            'type'        => $request->type,      // muda, corte, arreglo, gato
            'status'      => $request->status,    // pending, confirmed, cancelled, completed
            'is_difficult'=> $request->has('is_difficult'),
        ]);

        return redirect()->route('tenant.appointments.index');
    }

    /** FORMULARIO EDITAR */
    public function edit($id)
    {
        $appointment = Appointment::with(['customer', 'pet'])->findOrFail($id);
        $customers   = Cliente::orderBy('nombre')->get();
        $pets        = Mascota::orderBy('nombre')->get();

        return view('tenant.appointments.edit',
            compact('appointment', 'customers', 'pets')
        );
    }

    /** ACTUALIZAR */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $request->validate([
            'customer_id' => 'required',
            'date'        => 'required',
            'start_time'  => 'required',
            'type'        => 'required',
            'status'      => 'required'
        ]);

        $appointment->update([
            'customer_id' => $request->customer_id,
            'pet_id'      => $request->pet_id ?: null,
            'date'        => $request->date,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time ?: null,
            'notes'       => $request->notes,
            'type'        => $request->type,
            'status'      => $request->status,
            'is_difficult'=> $request->has('is_difficult'),
        ]);

        return redirect()->route('tenant.appointments.index');
    }

    /** ELIMINAR */
    public function destroy($id)
    {
        Appointment::findOrFail($id)->delete();
        return redirect()->route('tenant.appointments.index');
    }

    /** EVENTOS PARA FULLCALENDAR */
   public function calendarEvents()
{
    $appointments = Appointment::with(['customer', 'pet'])->get();

    $events = $appointments->map(function ($a) {

        // Asegurar formato correcto (YYYY-MM-DD)
        $date = substr($a->date, 0, 10);

        $start = $date . 'T' . $a->start_time;
        $end   = $a->end_time ? ($date . 'T' . $a->end_time) : null;

        // PRIORIDAD FINAL:
//
// 1) CANCELADA → azul
// 2) DIFÍCIL → gris
// 3) TIPO NORMAL
if ($a->status === 'cancelled') {
    $finalType = 'cancelled';
}
elseif ($a->is_difficult) {
    $finalType = 'dificiles';
}
else {
    $finalType = $a->type;
}


        return [
            'id'    => $a->id,
            'title' => ($a->pet->nombre ?? 'Mascota') . ' — ' . ($a->customer->nombre ?? 'Cliente'),
            'start' => $start,
            'end'   => $end,
            'extendedProps' => [
                'customer' => $a->customer->nombre ?? null,
                'pet'      => $a->pet->nombre ?? null,
                'notes'    => $a->notes,
                'type'     => $finalType,
            ],
        ];
    });

    return response()->json($events);
}

}
