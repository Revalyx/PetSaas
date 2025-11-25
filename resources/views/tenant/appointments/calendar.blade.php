@extends('layouts.tenant')

@section('title', 'Calendario de Citas')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- FullCalendar -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<style>

/* =============================================================================
   ESTILO GOOGLE CALENDAR - DARK MODE TAILORED
   ========================================================================== */

.fc { font-family: "Inter", sans-serif; }

.fc-toolbar-title {
    font-size: 1.45rem !important;
    font-weight: 600 !important;
    color: #fff !important;
}

/* BOTONES */
.fc-button {
    border-radius: 6px !important;
    background: #e7e8ea !important;
    border: none !important;
    color: #333 !important;
    padding: 6px 12px !important;
    font-weight: 500 !important;
}
.dark .fc-button {
    background: #2f3642 !important;
    color: #e5e5e5 !important;
}
.fc-button:hover { background:#d6d6d6 !important; }
.dark .fc-button:hover { background:#3d4655 !important; }

/* =============================================================================
   CALENDARIO MENSUAL
   ========================================================================== */

.dark .fc-daygrid-day-frame { border-color:#2c3342 !important; }
.dark .fc-theme-standard td,
.dark .fc-theme-standard th { border-color:#394457 !important; }
.fc .fc-daygrid-day-number { color:#e5e5e5 !important; font-weight:600; }
.fc-day-today { background:rgba(66,133,244,0.18) !important; border-radius:8px; }

/* =============================================================================
   TIMEGRID (SEMANA / DÍA)
   ========================================================================== */

.fc-timegrid .fc-col-header { position:relative !important; top:unset !important; z-index:1 !important; }
.fc-timegrid .fc-col-header-cell { background:#1f2633 !important; color:#e5e5e5 !important; border-color:#2e3544 !important; }
.fc-col-header-cell-cushion { font-size:1rem !important; font-weight:600 !important; text-transform:capitalize !important; padding:10px 0 !important; }

/* =============================================================================
   ❌ ELIMINACIÓN TOTAL ALL-DAY
   ========================================================================== */

.fc-timegrid-all-day,
.fc-timegrid-allday,
.fc-timegrid-axis-all-day,
.fc-timegrid-divider,
tr.fc-timegrid-allday,
tr.fc-timegrid-row.fc-timegrid-all-day {
    display:none !important;
    height:0 !important;
    padding:0 !important;
    margin:0 !important;
}

.fc-timegrid-body { margin-top:0 !important; }

/* =============================================================================
   COLORES SEGÚN TIPO
   ========================================================================== */

.fc-event.muda      { background-color:#34A853 !important; border-color:#34A853 !important; }
.fc-event.corte     { background-color:#EA4335 !important; border-color:#EA4335 !important; }
.fc-event.arreglo   { background-color:#FF4FB2 !important; border-color:#FF4FB2 !important; }
.fc-event.gato      { background-color:#FABB05 !important; border-color:#FABB05 !important; color:#000 !important; }

/* CANCELADAS (estado) */
.fc-event.cancelled {
    background-color:#4285F4 !important;
    border-color:#4285F4 !important;
}

/* DIFÍCILES (prioridad máxima) */
.fc-event.dificiles {
    background-color:#9AA0A6 !important;
    border-color:#9AA0A6 !important;
    color:white !important;
}

/* =============================================================================
   EVENTOS
   ========================================================================== */

.fc-event {
    border-radius:8px !important;
    padding:4px 8px !important;
    font-size:0.88rem !important;
    font-weight:500 !important;
    box-shadow:0 2px 5px rgba(0,0,0,0.12);
    transition:0.12s;
    color:white;
}
.fc-event:hover { filter:brightness(1.08); transform:scale(1.01); }

/* =============================================================================
   LÍNEA ROJA (HORA ACTUAL)
   ========================================================================== */

.fc-timegrid-now-indicator-line { border-color:#ea4335 !important; }
.fc-timegrid-now-indicator-arrow { border-color:#ea4335 transparent transparent !important; }

/* =============================================================================
   LEYENDA
   ========================================================================== */

.legend { display:flex; gap:12px; flex-wrap:wrap; align-items:center; }
.legend .item { display:flex; gap:6px; align-items:center; color:white; }
.legend .sw { width:16px; height:12px; border-radius:4px; }

/* =============================================================================
   MODAL
   ========================================================================== */

.modal-backdrop {
    position:fixed; inset:0;
    background:rgba(0,0,0,0.45);
    display:none; align-items:center; justify-content:center;
    z-index:50;
}

.modal {
    background:#1f2633; color:white;
    width:100%; max-width:700px;
    padding:20px; border-radius:12px;
}

.modal .row { display:flex; gap:10px; margin-bottom:8px; }
.modal .muted { color:#9aa0a6; }

</style>


<div class="space-y-6">

    <!-- HEADER SUPERIOR -->
    <div class="flex items-center justify-between bg-gray-100 dark:bg-gray-800/40 p-4 rounded-xl shadow">

        <div>
            <h2 class="text-2xl font-bold text-white">Calendario</h2>

            <div class="legend mt-2">

                <label class="item"><span class="sw" style="background:#34A853"></span><input type="checkbox" checked data-type="muda"> Muda</label>

                <label class="item"><span class="sw" style="background:#EA4335"></span><input type="checkbox" checked data-type="corte"> Corte</label>

                <label class="item"><span class="sw" style="background:#FF4FB2"></span><input type="checkbox" checked data-type="arreglo"> Arreglo</label>

                <label class="item"><span class="sw" style="background:#FABB05"></span><input type="checkbox" checked data-type="gato"> Gato</label>

                <label class="item"><span class="sw" style="background:#4285F4"></span><input type="checkbox" checked data-type="cancelled"> Canceladas</label>

                <label class="item"><span class="sw" style="background:#9AA0A6"></span><input type="checkbox" checked data-type="dificiles"> Difíciles</label>

            </div>
        </div>

        <div class="flex items-center gap-3">
            <button id="btn-today" class="px-3 py-2 rounded bg-gray-200 dark:bg-gray-700">Hoy</button>
            <button id="btn-month" class="px-3 py-2 rounded bg-gray-200 dark:bg-gray-700">Mensual</button>
            <button id="btn-week" class="px-3 py-2 rounded bg-gray-200 dark:bg-gray-700">Semanal</button>

            <a href="{{ route('tenant.appointments.create') }}"
               class="px-3 py-2 bg-blue-600 text-white rounded">Nueva Cita</a>
        </div>
    </div>

    <div id="calendar" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4"></div>

</div>

<!-- ================= MODAL ================= -->
<div id="modal" class="modal-backdrop">
    <div class="modal">
        <div class="flex justify-between mb-3">
            <h3 id="modal-title" class="text-xl font-semibold"></h3>
            <button id="modal-close" class="text-gray-300 text-xl">&times;</button>
        </div>

        <div id="modal-body">
            <div class="row"><div class="muted">Cliente:</div><div id="modal-customer"></div></div>
            <div class="row"><div class="muted">Mascota:</div><div id="modal-pet"></div></div>
            <div class="row"><div class="muted">Tipo:</div><div id="modal-type"></div></div>
            <div class="row"><div class="muted">Notas:</div><div id="modal-notes"></div></div>
            <div class="row"><div class="muted">Horario:</div><div id="modal-time"></div></div>
        </div>

        <div class="flex justify-end gap-3 mt-4">
            <a id="modal-edit" class="px-3 py-2 bg-yellow-600 text-white rounded">Editar</a>
            <button id="modal-delete" class="px-3 py-2 bg-red-600 text-white rounded">Eliminar</button>
            <button id="modal-close-2" class="px-3 py-2 bg-gray-600 text-white rounded">Cerrar</button>
        </div>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function() {

    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const calendarEl = document.getElementById('calendar');

    /* =========================================================================
       CONFIGURACIÓN DEL CALENDARIO
       ====================================================================== */

    const calendar = new FullCalendar.Calendar(calendarEl, {

        initialView: 'dayGridMonth',
        firstDay: 1,
        locale: 'es',
        navLinks: true,
        height: 'auto',
        stickyHeaderDates: false,
        allDaySlot: false,
        nowIndicator: true,
        selectable: true,
        editable: true,
        eventResizableFromStart: true,

        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },

        events: "{{ route('tenant.appointments.calendar.events') }}",

        eventDidMount(info) {

            info.el.classList.remove('muda','corte','arreglo','gato','cancelled','dificiles');

            const tipo = info.event.extendedProps.type;
            if (tipo) info.el.classList.add(tipo);

            info.el.setAttribute("data-event-type", tipo);
        },

        eventClick(info) {
            openModal(info.event);
        },

        datesSet(info) {
            applyFilter();
        }
    });

    calendar.render();



    /* =========================================================================
       FILTRO
       ====================================================================== */

    function applyFilter() {
        const activos = Array.from(document.querySelectorAll('.legend input[type="checkbox"]'))
            .filter(ch => ch.checked)
            .map(ch => ch.dataset.type);

        document.querySelectorAll('.fc-event').forEach(ev => {
            const tipo = ev.getAttribute("data-event-type");
            ev.style.display = activos.includes(tipo) ? '' : 'none';
        });
    }

    calendar.on('eventsSet', applyFilter);

    document.querySelectorAll('.legend input[type="checkbox"]').forEach(ch => {
        ch.addEventListener('change', applyFilter);
    });



    /* =========================================================================
       BOTONES DE VISTA
       ====================================================================== */

    document.getElementById('btn-today').addEventListener('click', () => {
        calendar.today();
        calendar.changeView('timeGridDay');
        applyFilter();
    });

    document.getElementById('btn-month').addEventListener('click', () => {
        calendar.changeView('dayGridMonth');
        applyFilter();
    });

    document.getElementById('btn-week').addEventListener('click', () => {
        calendar.changeView('timeGridWeek');
        applyFilter();
    });



    /* =========================================================================
       MODAL
       ====================================================================== */

    const modal = document.getElementById('modal');
    const modalClose = document.getElementById('modal-close');
    const modalClose2 = document.getElementById('modal-close-2');

    function openModal(event) {
        modal.style.display = 'flex';

        document.getElementById('modal-title').textContent = event.title;
        document.getElementById('modal-customer').textContent = event.extendedProps.customer || '-';
        document.getElementById('modal-pet').textContent = event.extendedProps.pet || '-';
        document.getElementById('modal-type').textContent = event.extendedProps.type || '-';
        document.getElementById('modal-notes').textContent = event.extendedProps.notes || '-';

        const s = new Date(event.start).toLocaleString();
        const e = event.end ? new Date(event.end).toLocaleString() : '';
        document.getElementById('modal-time').textContent = `${s} ${e ? '→ ' + e : ''}`;

        document.getElementById('modal-edit').href = `/tenant/appointments/${event.id}/edit`;

        document.getElementById('modal-delete').onclick = () => {
            if (!confirm('¿Eliminar cita?')) return;

            fetch(`/tenant/appointments/${event.id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrf }
            }).then(() => {
                modal.style.display = 'none';
                calendar.refetchEvents();
            });
        };
    }

    modalClose.onclick = () => modal.style.display = 'none';
    modalClose2.onclick = () => modal.style.display = 'none';

    modal.onclick = e => { if (e.target === modal) modal.style.display = 'none'; };

});
</script>

@endsection
