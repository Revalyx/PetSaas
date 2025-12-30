@extends('layouts.tenant')

@section('title', 'Calendario de Citas')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<style>
/* =====================================================
   PALETA CORPORATIVA ARIS
===================================================== */
:root {
    --aris-bg-light: #f8fafc;
    --aris-bg-dark: #020617;
    --aris-bg-dark-2: #0f172a;

    --aris-primary: #14b8a6;
    --aris-primary-hover: #0d9488;

    --aris-border: #1e293b;
    --aris-text-soft: #94a3b8;
    --aris-text-main: #e5e7eb;
}

/* =====================================================
   CONTENEDOR GENERAL
===================================================== */
.calendar-box {
    border-radius: 26px;
    padding: 28px;
    background: linear-gradient(180deg,#ffffff,#f1f5f9);
    box-shadow: 0 30px 80px rgba(0,0,0,.08);
}
.dark .calendar-box {
    background: linear-gradient(180deg,var(--aris-bg-dark-2),var(--aris-bg-dark));
}

/* =====================================================
   HEADER
===================================================== */
.calendar-header {
    display:flex;
    justify-content:space-between;
    align-items:flex-end;
    gap:20px;
}

.calendar-title {
    font-size: 2rem;
    font-weight: 800;
    color: var(--aris-primary);
}
.dark .calendar-title {
    color:white;
}

.calendar-subtitle {
    font-size:.9rem;
    color:#64748b;
}
.dark .calendar-subtitle {
    color:var(--aris-text-soft);
}

/* =====================================================
   FILTROS
===================================================== */
.filter-chip {
    display:flex;
    align-items:center;
    gap:10px;
    padding:7px 16px;
    border-radius:999px;
    background:white;
    border:1px solid #e5e7eb;
    font-size:.85rem;
    cursor:pointer;
    transition:.2s;
}
.filter-chip:hover {
    background:#f1f5f9;
}
.dark .filter-chip {
    background: var(--aris-bg-dark);
    border-color: var(--aris-border);
    color:#e5e7eb;
}

.filter-dot {
    width:12px;
    height:12px;
    border-radius:4px;
}

/* =====================================================
   BOTONES ARIS
===================================================== */
.cal-btn {
    padding:9px 18px;
    border-radius:12px;
    background:#e5e7eb;
    font-weight:600;
    font-size:.85rem;
    transition:.2s;
}
.cal-btn:hover {
    filter:brightness(.95);
}
.dark .cal-btn {
    background: var(--aris-bg-dark);
    color:#e5e7eb;
}

.cal-btn-primary {
    background: linear-gradient(135deg,var(--aris-primary),var(--aris-primary-hover));
    color:#0f172a;
}
.cal-btn-primary:hover {
    filter:brightness(1.05);
}

/* =====================================================
   FULLCALENDAR – INTEGRACIÓN ARIS
===================================================== */
.fc-toolbar { display:none; }

.fc {
    background: linear-gradient(180deg,#ffffff,#f1f5f9);
    border-radius: 22px;
    padding: 18px;
    border:1px solid #e2e8f0;
}
.dark .fc {
    background: linear-gradient(180deg,var(--aris-bg-dark-2),var(--aris-bg-dark));
    border-color: var(--aris-border);
    color:#e5e7eb;
}

/* HORAS */
.fc-timegrid-slot-label {
    font-size:.7rem;
    font-weight:500;
    color:#64748b;
}
.dark .fc-timegrid-slot-label {
    color:var(--aris-text-soft);
}

.fc-timegrid-slot {
    border-color:rgba(148,163,184,.15);
}

/* EVENTOS COMO CARDS */
.fc-event {
    border-radius: 14px !important;
    padding: 6px 8px;
    font-size:.78rem;
    font-weight:600;
    box-shadow:0 8px 20px rgba(0,0,0,.18);
    border:none!important;
    transition:.15s;
}
.fc-event:hover {
    transform: translateY(-1px);
    filter:brightness(1.05);
}

.fc-event-time {
    font-size:.65rem;
    opacity:.85;
}

/* =====================================================
   COLORES POR TIPO (ARIS)
===================================================== */
.fc-event.muda {
    background:linear-gradient(135deg,#22c55e,#16a34a)!important;
}
.fc-event.corte {
    background:linear-gradient(135deg,#ef4444,#b91c1c)!important;
}
.fc-event.arreglo {
    background:linear-gradient(135deg,#ec4899,#be185d)!important;
}
.fc-event.gato {
    background:linear-gradient(135deg,#facc15,#eab308)!important;
    color:#1f2937!important;
}
.fc-event.cancelled {
    background:linear-gradient(135deg,#3b82f6,#1d4ed8)!important;
}
.fc-event.dificiles {
    background:linear-gradient(135deg,#9ca3af,#6b7280)!important;
}

/* =====================================================
   EVENTOS CORTOS
===================================================== */
.fc-timegrid-event {
    min-height: 44px !important;
}
.fc-timegrid-event-short .fc-event-time {
    display:none;
}

/* =====================================================
   MODAL ARIS – ESTÉTICA CORPORATIVA
===================================================== */
.modal-backdrop {
    position:fixed;
    inset:0;
    background:linear-gradient(
        180deg,
        rgba(15,23,42,.85),
        rgba(2,6,23,.9)
    );
    display:none;
    align-items:center;
    justify-content:center;
    z-index:9999;
}

.modal {
    width:100%;
    max-width:560px;
    border-radius:28px;
    background:
        linear-gradient(
            180deg,
            #ffffff 0%,
            #f1f5f9 100%
        );
    padding:30px;
    box-shadow:
        0 60px 160px rgba(0,0,0,.55),
        inset 0 1px 0 rgba(255,255,255,.6);
    border:1px solid #e2e8f0;
    position:relative;
}

.dark .modal {
    background:
        linear-gradient(
            180deg,
            var(--aris-bg-dark-2),
            var(--aris-bg-dark)
        );
    border-color: var(--aris-border);
    box-shadow:
        0 60px 160px rgba(0,0,0,.75),
        inset 0 1px 0 rgba(255,255,255,.05);
    color:#e5e7eb;
}

/* TÍTULO */
.modal h3 {
    font-size:1.7rem;
    font-weight:800;
    margin-bottom:22px;
    color:var(--aris-primary);
}

.dark .modal h3 {
    color:#ffffff;
}

/* FILAS DE DATOS */
.modal-row {
    display:flex;
    align-items:flex-start;
    gap:12px;
    padding:10px 14px;
    margin-top:10px;
    border-radius:14px;
    background:rgba(20,184,166,.06);
}

.dark .modal-row {
    background:rgba(20,184,166,.10);
}

/* LABEL */
.modal-label {
    min-width:90px;
    font-weight:700;
    font-size:.8rem;
    text-transform:uppercase;
    letter-spacing:.04em;
    color:#0f766e;
}

.dark .modal-label {
    color:#5eead4;
}

/* VALOR */
.modal-row span:not(.modal-label) {
    font-size:.95rem;
    color:#0f172a;
}

.dark .modal-row span:not(.modal-label) {
    color:#e5e7eb;
}

/* FOOTER */
.modal-footer {
    margin-top:28px;
    display:flex;
    justify-content:flex-end;
    gap:14px;
}

</style>

<div class="calendar-box space-y-6">

    <div class="calendar-header">
        <div>
            <h1 class="calendar-title">Calendario de Citas</h1>
            <p class="calendar-subtitle">Gestión diaria de la agenda</p>
        </div>
    </div>

    {{-- FILTROS --}}
    <div class="flex flex-wrap gap-3">
        @php
            $types = [
                'muda' => '#22c55e',
                'corte' => '#ef4444',
                'arreglo' => '#ec4899',
                'gato' => '#facc15',
                'cancelled' => '#3b82f6',
                'dificiles' => '#9ca3af',
            ];
        @endphp

        @foreach($types as $type => $color)
        <label class="filter-chip">
            <span class="filter-dot" style="background:{{ $color }}"></span>
            <input type="checkbox" checked data-type="{{ $type }}">
            {{ ucfirst($type) }}
        </label>
        @endforeach
    </div>

    {{-- CONTROLES --}}
    <div class="flex flex-wrap gap-3">
        <button id="btn-day" class="cal-btn">Día</button>
        <button id="btn-week" class="cal-btn">Semana</button>
        <button id="btn-month" class="cal-btn">Mes</button>

        <a href="{{ route('tenant.appointments.create') }}"
           class="cal-btn cal-btn-primary">
            Nueva cita
        </a>
    </div>

    <div id="calendar"></div>
</div>

{{-- MODAL --}}
<div id="modal" class="modal-backdrop">
    <div class="modal">

        <h3 id="modal-title"></h3>

        <div class="modal-row"><span class="modal-label">Horario</span><span id="modal-time"></span></div>
        <div class="modal-row"><span class="modal-label">Cliente</span><span id="modal-customer"></span></div>
        <div class="modal-row"><span class="modal-label">Mascota</span><span id="modal-pet"></span></div>
        <div class="modal-row"><span class="modal-label">Tipo</span><span id="modal-type"></span></div>
        <div class="modal-row"><span class="modal-label">Notas</span><span id="modal-notes"></span></div>

        <div class="modal-footer">
            <a id="modal-edit" class="cal-btn cal-btn-primary">Editar cita</a>
            <button onclick="closeModal()" class="cal-btn">Cerrar</button>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const calendar = new FullCalendar.Calendar(
        document.getElementById('calendar'), {
            locale:'es',
            firstDay:1,
            initialView:'timeGridWeek',
            nowIndicator:true,
            height:'auto',
            slotMinTime:'08:00:00',
            slotMaxTime:'21:00:00',
            events:"{{ route('tenant.appointments.calendar.events') }}",

            eventDidMount(info) {
                const EP = info.event.extendedProps;

                if (EP.type) {
                    info.el.classList.add(EP.type);
                    info.el.dataset.type = EP.type;
                }

                if (EP.is_difficult) {
                    info.el.classList.add('dificiles');
                }

                if (info.event.end) {
                    const duration = (info.event.end - info.event.start) / 60000;
                    if (duration < 30) {
                        info.el.classList.add('fc-timegrid-event-short');
                    }
                }
            },

            eventClick(info) {
                openModal(info.event);
            }
        }
    );

    calendar.render();

    document.getElementById('btn-day').onclick = () => calendar.changeView('timeGridDay');
    document.getElementById('btn-week').onclick = () => calendar.changeView('timeGridWeek');
    document.getElementById('btn-month').onclick = () => calendar.changeView('dayGridMonth');

    function applyFilters() {
        const active = [...document.querySelectorAll('[data-type]:checked')]
            .map(c => c.dataset.type);

        document.querySelectorAll('.fc-event').forEach(ev => {
            ev.style.display = active.includes(ev.dataset.type) ? '' : 'none';
        });
    }

    document.querySelectorAll('[data-type]')
        .forEach(c => c.addEventListener('change', applyFilters));

    calendar.on('eventsSet', applyFilters);

    window.openModal = function(event) {
        document.getElementById('modal').style.display = 'flex';

        const EP = event.extendedProps;

        document.getElementById('modal-title').textContent = event.title;
        document.getElementById('modal-time').textContent =
            event.start.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'}) +
            (event.end ? ' – ' + event.end.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'}) : '');

        document.getElementById('modal-customer').textContent = EP.customer ?? '—';
        document.getElementById('modal-pet').textContent = EP.pet ?? '—';
        document.getElementById('modal-type').textContent = EP.type ?? '—';
        document.getElementById('modal-notes').textContent = EP.notes ?? '—';

        document.getElementById('modal-edit').href =
            `/tenant/appointments/${event.id}/edit`;
    };

    window.closeModal = function() {
        document.getElementById('modal').style.display = 'none';
    };
});
</script>

@endsection
