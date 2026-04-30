@extends('layouts.app')

@section('title', 'Calendar')
@section('page-title', 'Rental Calendar')
@section('breadcrumb', 'View all rentals in calendar format')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css">
<style>
.calendar-card {
    background: var(--black-2);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-sm);
    padding: 20px;
}
.calendar-legend {
    display: flex;
    gap: 20px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}
.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
}
.legend-dot {
    width: 12px;
    height: 12px;
    border-radius: 2px;
}
.fc {
    --fc-border-color: var(--border-subtle);
    --fc-button-text-color: var(--text-primary);
    --fc-button-bg-color: var(--black-3);
    --fc-button-border-color: var(--border-subtle);
    --fc-button-hover-bg-color: var(--gold-muted);
    --fc-button-hover-border-color: var(--gold);
    --fc-button-active-bg-color: var(--gold);
    --fc-button-active-border-color: var(--gold);
    --fc-event-bg-color: #38BDF8;
    --fc-event-border-color: #38BDF8;
    --fc-event-text-color: var(--black);
    --fc-today-bg-color: var(--gold-muted);
    --fc-page-bg-color: var(--black-2);
    --fc-neutral-bg-color: var(--black-3);
}
.fc .fc-toolbar-title {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 24px;
    color: var(--text-primary);
}
.fc .fc-col-header-cell-cushion {
    color: var(--text-muted);
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}
.fc .fc-daygrid-day-number {
    color: var(--text-primary);
    font-size: 13px;
}
.fc-event {
    cursor: pointer;
    font-size: 12px;
    font-weight: 500;
    padding: 2px 4px;
    border-radius: 3px;
}
.fc-event:hover {
    opacity: 0.9;
}
</style>
@endpush

@section('content')

<div class="calendar-legend">
    <div class="legend-item">
        <div class="legend-dot" style="background: #38BDF8;"></div>
        <span>Reserved</span>
    </div>
    <div class="legend-item">
        <div class="legend-dot" style="background: #22C55E;"></div>
        <span>Ongoing</span>
    </div>
    <div class="legend-item">
        <div class="legend-dot" style="background: #555550;"></div>
        <span>Completed</span>
    </div>
</div>

<div class="calendar-card">
    <div id="calendar"></div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        events: @json($rentals),
        eventClick: function(info) {
            if (info.event.url) {
                window.location.href = info.event.url;
            }
        },
        height: 'auto',
        firstDay: 0,
        buttonText: {
            today: 'Today',
            month: 'Month',
            week: 'Week',
            list: 'List'
        }
    });
    calendar.render();
});
</script>
@endpush
