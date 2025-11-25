<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentCalendarController extends Controller
{
    /**
     * Mostrar la vista del calendario (tabs mes/semana).
     */
    public function index()
    {
        return view('tenant.appointments.calendar');
    }

    /**
     * Devuelve los eventos en formato JSON para FullCalendar.
     * Opcionalmente recibe start/end por FullCalendar (ISO8601) para filtrar.
     */
    public function events(Request $request)
    {
        // Podemos filtrar por rango si FullCalendar lo envía
        $query = Appointment::with(['customer', 'pet']);

        if ($request->has('start') && $request->has('end')) {
            // start/end vienen como YYYY-MM-DD (o ISO) → filtramos por fecha
            $start = Carbon::parse($request->get('start'))->startOfDay();
            $end = Carbon::parse($request->get('end'))->endOfDay();
            $query->whereBetween('date', [$start->toDateString(), $end->toDateString()]);
        }

        $appointments = $query->get();

        $events = $appointments->map(function (Appointment $a) {
            // Construir start / end en formato ISO para FullCalendar
            // Si no hay start_time, usamos 09:00 por defecto visual (opcional)
            $date = $a->date ? $a->date->format('Y-m-d') : null;

            $start = null;
            $end = null;

            if ($date && $a->start_time) {
                // start_time stored like 'H:i:s' or 'H:i'
                $start = "{$date}T{$a->start_time}";
            } elseif ($date) {
                $start = "{$date}T09:00:00";
            }

            if ($date && $a->end_time) {
                $end = "{$date}T{$a->end_time}";
            } elseif ($date) {
                // si no hay end, poner +30 min visualmente
                try {
                    $base = $a->start_time ? Carbon::createFromFormat('H:i:s', $a->start_time) : Carbon::createFromFormat('H:i', '09:00');
                    $end = $base->addMinutes(30)->format('H:i:s');
                    $end = "{$date}T{$end}";
                } catch (\Exception $e) {
                    $end = "{$date}T09:30:00";
                }
            }

            // Título: Mascota — Cliente (si hay mascota, sino solo cliente)
            $titleParts = [];
            if ($a->pet) $titleParts[] = $a->pet->nombre;
            if ($a->customer) $titleParts[] = $a->customer->nombre . ($a->customer->apellidos ? " {$a->customer->apellidos}" : '');
            $title = implode(' — ', $titleParts) ?: 'Cita';

            // Color por estado
            $colorMap = [
                'pending'   => '#6b7280', // gris
                'confirmed' => '#2563eb', // azul
                'cancelled' => '#dc2626', // rojo
                'completed' => '#16a34a', // verde
            ];

            $status = $a->status ?? 'pending';
            $color = $colorMap[$status] ?? '#6b7280';

            return [
                'id' => $a->id,
                'title' => $title,
                'start' => $start,
                'end' => $end,
                'status' => $status,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'customer' => $a->customer?->nombre . ' ' . ($a->customer?->apellidos ?? ''),
                    'pet' => $a->pet?->nombre,
                    'notes' => $a->notes,
                ],
            ];
        });

        return response()->json($events->values());
    }
}
