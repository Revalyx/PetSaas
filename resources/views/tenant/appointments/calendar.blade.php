@extends('layouts.tenant')

@section('title', 'Calendario de Citas')

@section('content')

<!-- ============================= -->
<!-- FullCalendar: estilos + JS   -->
<!-- ============================= -->

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<div class="space-y-6">

    <!-- HEADER DEL CALENDARIO -->
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold">Calendario</h2>

        <div class="flex items-center gap-3">

            <button id="btn-month"
                class="px-3 py-2 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                Mensual
            </button>

            <button id="btn-week"
                class="px-3 py-2 bg-gray-200 dark:bg-gray-700 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                Semanal
            </button>

            <a href="{{ route('tenant.appointments.create') }}"
               class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Nueva Cita
            </a>
        </div>
    </div>

    <!-- CALENDARIO -->
    <div id="calendar" class="bg-white dark:bg-gray-800 rounded-xl shadow p-4"></div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',     // vista inicial: mensual
        firstDay: 1,                     // semana empieza lunes
        height: 'auto',
        selectable: true,
        nowIndicator: true,
        navLinks: true,
        eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },

        // ============================
        // CARGA DE EVENTOS POR AJAX
        // ============================
        events: {
            url: "{{ route('tenant.appointments.calendar.events') }}",
            method: 'GET'
        },

        // Tooltip + props extra
        eventDidMount: function(info) {
            let extra = info.event.extendedProps;
            let t = info.event.title;

            if (extra.customer) t += "\nCliente: " + extra.customer;
            if (extra.pet)      t += "\nMascota: " + extra.pet;
            if (extra.notes)    t += "\nNotas: " + extra.notes;

            info.el.setAttribute("title", t);
        },

        // Clic en una cita → editarla
        eventClick: function(info) {
            window.location.href = "/tenant/appointments/" + info.event.id + "/edit";
        },

        // Clic en un día vacío → crear cita con la fecha
        dateClick: function(info) {
            window.location.href = "{{ route('tenant.appointments.create') }}?date=" + info.dateStr;
        }
    });

    calendar.render();

    // BOTONES PARA CAMBIAR ENTRE VISTA MENSUAL Y SEMANAL
    document.getElementById('btn-month').addEventListener('click', () => {
        calendar.changeView('dayGridMonth');
    });

    document.getElementById('btn-week').addEventListener('click', () => {
        calendar.changeView('timeGridWeek');
    });

});
</script>

@endsection
