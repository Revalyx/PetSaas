@extends('layouts.tenant')

@section('title', 'Calendario de Citas')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<style>
/* =====================================================
   PALETA ARIS
===================================================== */
:root {
    --aris-bg-light: #f8fafc;
    --aris-bg-dark: #020617;
    --aris-bg-dark-2: #0f172a;

    --aris-primary: #14b8a6;        /* TEAL ARIS */
    --aris-primary-hover: #0d9488;

    --aris-border: #1e293b;
    --aris-text-soft: #94a3b8;
    --aris-text-main: #e5e7eb;
}


/* =====================================================
   CONTENEDOR GENERAL
===================================================== */
.calendar-box {
    border-radius: 22px;
    padding: 26px;
    background: var(--aris-bg-light);
    box-shadow: 0 20px 40px rgba(0,0,0,.08);
}
.dark .calendar-box {
    background: linear-gradient(180deg,var(--aris-bg-dark-2),var(--aris-bg-dark));
}

/* =====================================================
   TÍTULO
===================================================== */
.calendar-title {
    font-size: 1.9rem;
    font-weight: 700;
    color: var(--aris-primary);
}
.dark .calendar-title {
    color: #ffffff;
}

/* =====================================================
   FILTROS
===================================================== */
.filter-chip {
    display:flex;
    align-items:center;
    gap:10px;
    padding:6px 14px;
    border-radius:999px;
    background:white;
    border:1px solid #e5e7eb;
    font-size:.9rem;
    cursor:pointer;
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
    padding:8px 16px;
    border-radius:10px;
    background:#e5e7eb;
    font-weight:500;
}
.dark .cal-btn {
    background: var(--aris-bg-dark);
    color:#e5e7eb;
}

.cal-btn-primary {
    background: var(--aris-primary);
    color:#1f2937;
    font-weight:600;
}
.cal-btn-primary:hover {
    background: var(--aris-primary-hover);
}

/* =====================================================
   FULLCALENDAR BASE
===================================================== */
.fc-toolbar { display:none; }

.fc {
    background:white;
    border-radius:16px;
    padding:14px;
}
.dark .fc {
    background: var(--aris-bg-dark);
    color:#e5e7eb;
}

.fc-event {
    border-radius:8px!important;
    font-size:.85rem;
    padding:4px 6px;
}

/* =====================================================
   COLORES POR TIPO (ARIS)
===================================================== */
.fc-event.muda       { background:#22c55e!important; }
.fc-event.corte      { background:#ef4444!important; }
.fc-event.arreglo    { background:#ec4899!important; }
.fc-event.gato       { background:#facc15!important; color:black!important; }
.fc-event.cancelled  { background:#3b82f6!important; }
.fc-event.dificiles  { background:#9ca3af!important; }

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
   MODAL ARIS
===================================================== */
.modal-backdrop {
    position:fixed;
    inset:0;
    background:rgba(2,6,23,.75);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:9999;
}

.modal {
    width:100%;
    max-width:520px;
    border-radius:22px;
    background: linear-gradient(180deg,#ffffff,#f8fafc);
    padding:26px;
    box-shadow:0 40px 120px rgba(0,0,0,.45);
    border:1px solid #e5e7eb;
}
.dark .modal {
    background: linear-gradient(180deg,var(--aris-bg-dark-2),var(--aris-bg-dark));
    border-color: var(--aris-border);
    color:#e5e7eb;
}

.modal h3 {
    font-size:1.5rem;
    font-weight:700;
    margin-bottom:14px;
    color: var(--aris-text-main);
}
.dark .modal h3 {
    color:white;
}

.modal-row {
    display:flex;
    gap:8px;
    margin-top:10px;
    font-size:.95rem;
}

.modal-label {
    min-width:80px;
    font-weight:600;
    color:#475569;
}
.dark .modal-label {
    color: var(--aris-text-soft);
}

.modal-footer {
    margin-top:24px;
    display:flex;
    justify-content:flex-end;
    gap:10px;
}
</style>

<div class="calendar-box space-y-6">

    <h1 class="calendar-title">Calendario de Citas</h1>

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
    <div class="flex gap-3">
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
