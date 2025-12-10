@extends('layouts.tenant')

@section('title', 'Calendario de Citas')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- FullCalendar -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<style>
/* ================================================================
   ESTILO GOOGLE CALENDAR - DARK MODE
================================================================ */

.fc { font-family: "Inter", sans-serif; }

.fc-toolbar-title {
    font-size: 1.45rem !important;
    font-weight: 600 !important;
    color: #fff !important;
}

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

.dark .fc-daygrid-day-frame { border-color:#2c3342 !important; }
.dark .fc-theme-standard td,
.dark .fc-theme-standard th { border-color:#394457 !important; }

.fc .fc-daygrid-day-number { color:#e5e5e5 !important; font-weight:600; }

.fc-day-today {
    background:rgba(66,133,244,0.18) !important;
    border-radius:8px;
}

/* Colores por tipo */
.fc-event.muda      { background-color:#34A853 !important; border-color:#34A853 !important; }
.fc-event.corte     { background-color:#EA4335 !important; border-color:#EA4335 !important; }
.fc-event.arreglo   { background-color:#FF4FB2 !important; border-color:#FF4FB2 !important; }
.fc-event.gato      { background-color:#FABB05 !important; border-color:#FABB05 !important; color:#000 !important; }
.fc-event.cancelled { background-color:#4285F4 !important; border-color:#4285F4 !important; }
.fc-event.dificiles { background-color:#9AA0A6 !important; border-color:#9AA0A6 !important; }

.fc-event {
    border-radius: 8px !important;
    padding: 4px 8px !important;
    font-size: 0.88rem !important;
    font-weight: 500 !important;
    box-shadow: 0 2px 5px rgba(0,0,0,0.12);
    transition: 0.12s;
    color: white !important;
}

.fc-event:hover {
    transform: scale(1.02);
    filter: brightness(1.08);
}

/* Leyenda */
.legend {
    display:flex;
    flex-wrap:wrap;
    gap:14px;
    align-items:center;
    margin-top: 8px;
}

.legend .item {
    display:flex;
    align-items:center;
    gap:6px;
    background:rgba(255,255,255,0.07);
    padding:6px 12px;
    border-radius:8px;
    color:white;
    border:1px solid rgba(255,255,255,0.1);
}

.legend .sw {
    width:14px;
    height:14px;
    border-radius:4px;
}

/* ==============================
   MODAL DE DETALLES — Google Style
============================== */

.modal-backdrop {
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.45);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:9999;
    backdrop-filter:blur(3px);
}

.modal {
    background:#1f2633;
    width:100%;
    max-width:620px;
    padding:0;
    border-radius:14px;
    overflow:hidden;
    box-shadow:0 12px 28px rgba(0,0,0,0.35);
    color:white;
}

.modal-header {
    padding:18px 20px;
    border-bottom:1px solid rgba(255,255,255,0.07);
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.modal-header h3 {
    font-size:1.30rem;
    font-weight:600;
}

.modal-close-btn {
    font-size:1.6rem;
    cursor:pointer;
    color:#ccc;
}
.modal-close-btn:hover { color:white; }

.modal-body { padding:18px 20px; font-size:0.95rem; }

.modal-row { margin-bottom:14px; display:flex; }

.modal-label {
    width:115px;
    color:#8d97a9;
    font-weight:500;
}

.modal-value { flex:1; }

.modal-footer {
    padding:14px 20px;
    border-top:1px solid rgba(255,255,255,0.07);
    display:flex;
    justify-content:flex-end;
    gap:10px;
}

.modal-btn {
    padding:8px 14px;
    border-radius:6px;
    font-weight:500;
}
.modal-btn-edit { background:#d6a100; color:black; }
.modal-btn-delete { background:#dc2626; color:white; }
.modal-btn-close { background:#4b5563; color:white; }
.modal-btn:hover { filter:brightness(1.1); }

/* ==========================
   MODAL DE ELIMINACIÓN
========================== */

#modal-delete {
    display:none;
}

</style>


{{-- ================================
     LISTA FILTROS + HEADER
================================ --}}
<div class="space-y-6">

    <div class="bg-gray-100 dark:bg-gray-800/40 p-4 rounded-xl shadow">

        <div class="flex flex-col gap-4">

            <div>
                <h2 class="text-2xl font-bold text-white">Calendario de Citas</h2>

                <div class="legend mt-3">

                    <label class="item">
                        <span class="sw" style="background:#34A853"></span>
                        <input type="checkbox" checked data-type="muda"> Muda
                    </label>

                    <label class="item">
                        <span class="sw" style="background:#EA4335"></span>
                        <input type="checkbox" checked data-type="corte"> Corte
                    </label>

                    <label class="item">
                        <span class="sw" style="background:#FF4FB2"></span>
                        <input type="checkbox" checked data-type="arreglo"> Arreglo
                    </label>

                    <label class="item">
                        <span class="sw" style="background:#FABB05"></span>
                        <input type="checkbox" checked data-type="gato"> Gato
                    </label>

                    <label class="item">
                        <span class="sw" style="background:#4285F4"></span>
                        <input type="checkbox" checked data-type="cancelled"> Canceladas
                    </label>

                    <label class="item">
                        <span class="sw" style="background:#9AA0A6"></span>
                        <input type="checkbox" checked data-type="dificiles"> Difíciles
                    </label>

                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <button id="btn-today" class="px-3 py-2 rounded bg-gray-200 dark:bg-gray-700">Hoy</button>
                <button id="btn-month" class="px-3 py-2 rounded bg-gray-200 dark:bg-gray-700">Mensual</button>
                <button id="btn-week" class="px-3 py-2 rounded bg-gray-200 dark:bg-gray-700">Semanal</button>

                <a href="{{ route('tenant.appointments.create') }}"
                   class="px-3 py-2 bg-blue-600 text-white rounded">Nueva Cita</a>
            </div>

        </div>

    </div>

    <div id="calendar" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4"></div>

</div>



{{-- ====================================================
     MODAL — DETALLES DE CITA
==================================================== --}}
<div id="modal" class="modal-backdrop">
    <div class="modal">

        <div class="modal-header">
            <h3 id="modal-title"></h3>
            <span id="modal-close" class="modal-close-btn">&times;</span>
        </div>

        <div class="modal-body">

            <div class="modal-row">
                <div class="modal-label">Cliente:</div>
                <div id="modal-customer" class="modal-value"></div>
            </div>

            <div class="modal-row">
                <div class="modal-label">Mascota:</div>
                <div id="modal-pet" class="modal-value"></div>
            </div>

            <div class="modal-row">
                <div class="modal-label">Tipo:</div>
                <div id="modal-type" class="modal-value"></div>
            </div>

            <div class="modal-row">
                <div class="modal-label">Notas:</div>
                <div id="modal-notes" class="modal-value"></div>
            </div>

            <div class="modal-row">
                <div class="modal-label">Horario:</div>
                <div id="modal-time" class="modal-value"></div>
            </div>

        </div>

        <div class="modal-footer">
            <a id="modal-edit" class="modal-btn modal-btn-edit">Editar</a>
            <button id="modal-delete-btn" class="modal-btn modal-btn-delete">Eliminar</button>
            <button id="modal-close-2" class="modal-btn modal-btn-close">Cerrar</button>
        </div>

    </div>
</div>



{{-- ====================================================
     MODAL — ELIMINACIÓN GLOBAL (UNIFICADO)
==================================================== --}}
<div id="modal-delete" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden justify-center items-center z-50">

    <div class="bg-gray-800 text-white p-6 rounded-xl shadow-xl w-full max-w-md">

        <h2 class="text-xl font-bold mb-4">Confirmar eliminación</h2>
        <p class="text-gray-300 mb-6">¿Seguro que deseas eliminar esta cita?</p>

        <div class="flex justify-end gap-3">

            <button onclick="closeDeleteModal()"
                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded-lg">
                Cancelar
            </button>

            <button onclick="confirmDelete()"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg">
                Eliminar
            </button>

        </div>

    </div>

</div>



<script>
document.addEventListener('DOMContentLoaded', function() {

    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const calendarEl = document.getElementById('calendar');

    const fmt = new Intl.DateTimeFormat("es-ES", {
        year: "numeric", month: "2-digit", day: "2-digit",
        hour: "2-digit", minute: "2-digit"
    });

    /* ============================================
       FULLCALENDAR
    ============================================ */
    const calendar = new FullCalendar.Calendar(calendarEl, {

        initialView: 'timeGridDay',
        firstDay: 1,
        locale: 'es',
        navLinks: true,
        height: 700,
        contentHeight: "auto",
        stickyHeaderDates: false,
        nowIndicator: true,

        events: "{{ route('tenant.appointments.calendar.events') }}",

        eventDidMount(info) {
            info.el.classList.remove('muda','corte','arreglo','gato','cancelled','dificiles');
            const tipo = info.event.extendedProps.type;
            if (tipo) info.el.classList.add(tipo);
            info.el.setAttribute("data-event-type", tipo);
        },

        eventClick(info) {
            openModal(info.event);
        }

    });

    calendar.render();


    /* ============================================
       FILTROS
    ============================================ */
    function applyFilter() {
        const activos = Array.from(
            document.querySelectorAll('.legend input[type="checkbox"]')
        )
        .filter(ch => ch.checked)
        .map(ch => ch.dataset.type);

        document.querySelectorAll('.fc-event').forEach(ev => {
            const tipo = ev.getAttribute("data-event-type");
            ev.style.display = activos.includes(tipo) ? '' : 'none';
        });
    }

    document.querySelectorAll('.legend input').forEach(ch => {
        ch.addEventListener('change', applyFilter);
    });

    calendar.on('eventsSet', applyFilter);


    /* ============================================
       VISTAS
    ============================================ */
    document.getElementById('btn-today').onclick = () => calendar.today();
    document.getElementById('btn-month').onclick = () => calendar.changeView('dayGridMonth');
    document.getElementById('btn-week').onclick = () => calendar.changeView('timeGridWeek');


    /* ============================================
       MODAL DETALLES
    ============================================ */
    const modal = document.getElementById('modal');

    function openModal(event) {

        modal.style.display = "flex";

        const EP = event.extendedProps;

        document.getElementById('modal-title').textContent = event.title;
        document.getElementById('modal-customer').textContent = EP.customer ?? '-';
        document.getElementById('modal-pet').textContent = EP.pet ?? '-';
        document.getElementById('modal-type').textContent = EP.type ?? '-';
        document.getElementById('modal-notes').textContent = EP.notes ?? '-';

        const s = fmt.format(event.start);
        const e = event.end ? fmt.format(event.end) : '';
        document.getElementById('modal-time').textContent = e ? `${s} → ${e}` : s;

        document.getElementById('modal-edit').href =
            `/tenant/appointments/${event.id}/edit`;

        document.getElementById('modal-delete-btn').onclick = () => {
            closeModal();
            openDeleteModalFromCalendar(event.id);
        };
    }

    function closeModal() {
        modal.style.display = "none";
    }

    document.getElementById('modal-close').onclick = closeModal;
    document.getElementById('modal-close-2').onclick = closeModal;

    modal.onclick = e => {
        if (e.target === modal) closeModal();
    };


    /* ============================================
       MODAL ELIMINACIÓN GLOBAL
    ============================================ */
    let deleteEventId = null;

    window.openDeleteModalFromCalendar = function(id) {
        deleteEventId = id;
        const md = document.getElementById("modal-delete");
        md.classList.remove("hidden");
        md.classList.add("flex");
    };

    window.closeDeleteModal = function() {
        const md = document.getElementById("modal-delete");
        md.classList.add("hidden");
        md.classList.remove("flex");
    };

    window.confirmDelete = function() {

        fetch(`/tenant/appointments/${deleteEventId}`, {
            method: "DELETE",
            headers: { "X-CSRF-TOKEN": csrf }
        })
        .then(() => {
            closeDeleteModal();
            calendar.refetchEvents();
        });
    };

});
</script>

@endsection
