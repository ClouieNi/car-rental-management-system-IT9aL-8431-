<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Cars ni Bai') — Rental Management</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --gold:          #E8A020;
            --gold-dark:     #C47D10;
            --gold-light:    #F0B545;
            --gold-muted:    rgba(232,160,32,0.10);
            --black:         #0E0E0E;
            --black-2:       #161616;
            --black-3:       #1E1E1E;
            --black-4:       #272727;
            --surface:       #1A1A1A;
            --surface-2:     #222222;
            --border:        rgba(255,184,0,0.15);
            --border-subtle: rgba(255,255,255,0.06);
            --text-primary:  #F5F1E8;
            --text-muted:    #888880;
            --text-dim:      #555550;
            --success:       #22C55E;
            --warning:       #F59E0B;
            --danger:        #EF4444;
            --info:          #38BDF8;
            --sidebar-w:     240px;
            --radius:        10px;
            --radius-sm:     6px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
    height: 100%;
    font-family: 'Poppins', sans-serif;
            background: var(--black);
            color: var(--text-primary);
            font-size: 14px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: var(--black-2); }
        ::-webkit-scrollbar-thumb { background: var(--gold-dark); border-radius: 2px; }

        .app-shell { display: flex; min-height: 100vh; }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--black-2);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            transition: transform .25s ease;
        }

        .sidebar-logo {
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-logo .logo-icon {
            width: 40px; height: 40px;
            background: var(--gold);
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; flex-shrink: 0;
        }

        .sidebar-logo .logo-name {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 20px; letter-spacing: 1px; color: var(--gold);
        }

        .sidebar-logo .logo-sub {
            font-size: 10px; color: var(--text-muted);
            text-transform: uppercase; letter-spacing: 1.5px;
        }

        .sidebar-nav { flex: 1; padding: 16px 0; overflow-y: auto; }

        .nav-section-label {
            font-size: 10px; font-weight: 600; color: var(--text-dim);
            text-transform: uppercase; letter-spacing: 1.5px;
            padding: 8px 20px 4px; margin-top: 8px;
        }

        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 20px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 13.5px; font-weight: 500;
            transition: all .15s ease;
            border-left: 3px solid transparent;
        }

        .nav-item i { font-size: 16px; width: 20px; flex-shrink: 0; }
        .nav-item:hover { color: var(--text-primary); background: var(--gold-muted); }
        .nav-item.active { color: var(--gold); background: var(--gold-muted); border-left-color: var(--gold); }

        .nav-item .nav-badge {
            margin-left: auto; background: var(--gold); color: var(--black);
            font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 20px;
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid var(--border-subtle);
        }

        .user-card {
            display: flex; align-items: center; gap: 10px;
            padding: 10px; background: var(--black-3);
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-subtle);
        }

        .user-avatar {
            width: 34px; height: 34px; background: var(--gold);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-weight: 700; color: var(--black); font-size: 13px; flex-shrink: 0;
        }

        .user-info { flex: 1; min-width: 0; }
        .user-info .user-name { font-size: 13px; font-weight: 600; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-info .user-role { font-size: 10px; color: var(--gold); text-transform: uppercase; letter-spacing: 1px; }

        .logout-btn {
            background: none; border: none; color: var(--text-dim);
            cursor: pointer; padding: 4px; border-radius: 4px;
            transition: color .15s; font-size: 15px;
        }
        .logout-btn:hover { color: var(--danger); }

        /* MAIN */
        .main-content { flex: 1; margin-left: var(--sidebar-w); display: flex; flex-direction: column; min-height: 100vh; }

        .top-header {
            height: 64px; background: var(--black-2);
            border-bottom: 1px solid var(--border-subtle);
            display: flex; align-items: center; padding: 0 28px; gap: 16px;
            position: sticky; top: 0; z-index: 50;
        }

        .page-title-wrap { flex: 1; }
        .page-title-wrap h1 { font-family: 'Bebas Neue', sans-serif; font-size: 22px; letter-spacing: 1.5px; line-height: 1; }
        .page-title-wrap .breadcrumb { font-size: 11px; color: var(--text-muted); margin-top: 2px; }
        .page-title-wrap .breadcrumb span { color: var(--gold); }

        .header-actions { display: flex; align-items: center; gap: 10px; }

        .header-icon-btn {
            width: 36px; height: 36px; background: var(--black-3);
            border: 1px solid var(--border-subtle); border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-muted); text-decoration: none; font-size: 16px;
            transition: all .15s; position: relative;
        }
        .header-icon-btn:hover { background: var(--gold-muted); color: var(--gold); }

        .notif-dot {
            position: absolute; top: 7px; right: 7px;
            width: 7px; height: 7px; background: var(--gold);
            border-radius: 50%; border: 2px solid var(--black-2);
        }

        .page-body { flex: 1; padding: 28px; background: var(--black); }

        /* CARDS */
        .card { background: var(--surface); border: 1px solid var(--border-subtle); border-radius: var(--radius); overflow: hidden; }
        .card-header { padding: 16px 20px; border-bottom: 1px solid var(--border-subtle); display: flex; align-items: center; justify-content: space-between; gap: 12px; }
        .card-title { font-weight: 600; font-size: 14px; color: var(--text-primary); display: flex; align-items: center; gap: 8px; }
        .card-title i { color: var(--gold); font-size: 15px; }
        .card-body { padding: 20px; }

        /* STAT CARDS */
        .stat-card {
            background: var(--surface); border: 1px solid var(--border-subtle);
            border-radius: var(--radius); padding: 20px; position: relative; overflow: hidden;
            transition: border-color .15s, transform .15s;
        }
        .stat-card:hover { border-color: var(--border); transform: translateY(-1px); }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: var(--gold); }
        .stat-card.green::before { background: var(--success); }
        .stat-card.blue::before  { background: var(--info); }
        .stat-card.red::before   { background: var(--danger); }
        .stat-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted); margin-bottom: 8px; }
        .stat-value { font-family: 'Bebas Neue', sans-serif; font-size: 32px; letter-spacing: 1px; line-height: 1; }
        .stat-sub { font-size: 11px; color: var(--text-muted); margin-top: 6px; }
        .stat-icon { position: absolute; top: 20px; right: 20px; font-size: 28px; opacity: 0.15; }

        /* BUTTONS */
        .btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: var(--radius-sm);
    font-size: 13px; font-weight: 600; font-family: 'Poppins', sans-serif;
        }
        .btn-gold { background: var(--gold); color: var(--black); border-color: var(--gold); }
        .btn-gold:hover { background: var(--gold-light); color: var(--black); text-decoration: none; }
        .btn-outline { background: transparent; color: var(--text-muted); border-color: var(--border-subtle); }
        .btn-outline:hover { background: var(--black-4); color: var(--text-primary); border-color: var(--border); text-decoration: none; }
        .btn-danger { background: rgba(239,68,68,0.1); color: var(--danger); border-color: rgba(239,68,68,0.25); }
        .btn-danger:hover { background: rgba(239,68,68,0.2); }
        .btn-sm { padding: 5px 12px; font-size: 12px; }
        .btn-lg { padding: 12px 24px; font-size: 15px; }

        /* TABLES */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { padding: 10px 16px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-dim); background: var(--black-2); border-bottom: 1px solid var(--border-subtle); text-align: left; white-space: nowrap; }
        .data-table td { padding: 13px 16px; border-bottom: 1px solid var(--border-subtle); color: var(--text-primary); font-size: 13.5px; }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover td { background: var(--gold-muted); }

        /* BADGES */
        .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; }
        .badge-success   { background: rgba(34,197,94,.15);  color: #4ADE80; }
        .badge-warning   { background: rgba(245,158,11,.15); color: #FCD34D; }
        .badge-danger    { background: rgba(239,68,68,.15);  color: #F87171; }
        .badge-info      { background: rgba(56,189,248,.15); color: #7DD3FC; }
        .badge-gold      { background: var(--gold-muted);    color: var(--gold); }
        .badge-secondary { background: rgba(255,255,255,.08);color: var(--text-muted); }

        /* FORMS */
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: .8px; margin-bottom: 6px; }
.form-control { width: 100%; background: var(--black-3); border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); color: var(--text-primary); padding: 9px 12px; font-family: 'Poppins', sans-serif;        .form-control::placeholder { color: var(--text-dim); }
        select.form-control option { background: var(--black-3); }
        textarea.form-control { resize: vertical; min-height: 80px; }

        /* ALERTS */
        .alert { padding: 12px 16px; border-radius: var(--radius-sm); font-size: 13px; border-left: 3px solid; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
        .alert-success { background: rgba(34,197,94,.08);  border-color: var(--success); color: #4ADE80; }
        .alert-warning { background: rgba(245,158,11,.08); border-color: var(--warning); color: #FCD34D; }
        .alert-danger  { background: rgba(239,68,68,.08);  border-color: var(--danger);  color: #F87171; }
        .alert-info    { background: rgba(56,189,248,.08); border-color: var(--info);    color: #7DD3FC; }

        /* GRID */
        .grid { display: grid; gap: 16px; }
        .grid-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-4 { grid-template-columns: repeat(4, 1fr); }

        /* SECTION HEADER */
        .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; gap: 16px; }
        .section-header h2 { font-family: 'Bebas Neue', sans-serif; font-size: 28px; letter-spacing: 2px; line-height: 1; }
        .section-header p { font-size: 13px; color: var(--text-muted); margin-top: 2px; }

        /* MOBILE */
        .menu-toggle { display: none; background: none; border: 1px solid var(--border-subtle); color: var(--text-primary); padding: 6px 10px; border-radius: var(--radius-sm); cursor: pointer; font-size: 16px; }

        @media (max-width: 900px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .menu-toggle { display: flex; }
            .grid-4 { grid-template-columns: repeat(2, 1fr); }
            .grid-3 { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 600px) {
            .page-body { padding: 16px; }
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
        }
    </style>

    @stack('styles')
</head>
<body>
<div class="app-shell">

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">🚗</div>
            <div class="logo-text">
                <div class="logo-name">Cars ni Bai</div>
                <div class="logo-sub">Rental Management</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-label">Overview</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>

            <div class="nav-section-label">Fleet</div>
            <a href="{{ route('cars.index') }}" class="nav-item {{ request()->routeIs('cars.*') ? 'active' : '' }}">
                <i class="bi bi-car-front"></i> Fleet Management
            </a>
            <a href="{{ route('suppliers.index') }}" class="nav-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                <i class="bi bi-building"></i> Suppliers
            </a>

            <div class="nav-section-label">Rentals</div>
            <a href="{{ route('rentals.index') }}" class="nav-item {{ request()->routeIs('rentals.index') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Transactions
                @php $activeCount = \App\Models\Rental::active()->count(); @endphp
                @if($activeCount > 0)
                    <span class="nav-badge">{{ $activeCount }}</span>
                @endif
            </a>
            <a href="{{ route('rentals.create') }}" class="nav-item {{ request()->routeIs('rentals.create') ? 'active' : '' }}">
                <i class="bi bi-plus-circle"></i> New Rental
            </a>
            <a href="{{ route('rentals.calendar') }}" class="nav-item {{ request()->routeIs('rentals.calendar') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i> Calendar
            </a>
            <a href="{{ route('rentals.mastersheet') }}" class="nav-item {{ request()->routeIs('rentals.mastersheet') ? 'active' : '' }}">
                <i class="bi bi-table"></i> Master Sheet
            </a>

            <div class="nav-section-label">Quotes</div>
            <a href="{{ route('quotes.index') }}" class="nav-item {{ request()->routeIs('quotes.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text"></i> Quote Requests
                @php $pendingQuotes = \App\Models\Quote::pending()->count(); @endphp
                @if($pendingQuotes > 0)
                    <span class="nav-badge">{{ $pendingQuotes }}</span>
                @endif
            </a>

            <div class="nav-section-label">Customers</div>
            <a href="{{ route('messages.index') }}" class="nav-item {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                <i class="bi bi-chat-square-text"></i> Messages
                @php $unread = \App\Models\CustomerMessage::where('is_read', false)->count(); @endphp
                @if($unread > 0)
                    <span class="nav-badge">{{ $unread }}</span>
                @endif
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">{{ auth()->user()->role }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div class="main-content">
        <header class="top-header">
            <button class="menu-toggle" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <div class="page-title-wrap">
                <h1>@yield('page-title', 'Dashboard')</h1>
                <div class="breadcrumb">@yield('breadcrumb', 'Cars ni Bai')</div>
            </div>
            <div class="header-actions">
                <a href="{{ route('rentals.create') }}" class="btn btn-gold btn-sm">
                    <i class="bi bi-plus"></i> New Rental
                </a>
                <a href="{{ route('messages.index') }}" class="header-icon-btn">
                    <i class="bi bi-chat"></i>
                    @if(\App\Models\CustomerMessage::where('is_read', false)->count() > 0)
                        <span class="notif-dot"></span>
                    @endif
                </a>
            </div>
        </header>

        <main class="page-body">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<div id="sidebar-overlay"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:99"
     onclick="toggleSidebar()"></div>

<script>
function toggleSidebar() {
    const s = document.getElementById('sidebar');
    const o = document.getElementById('sidebar-overlay');
    s.classList.toggle('open');
    o.style.display = s.classList.contains('open') ? 'block' : 'none';
}
document.querySelectorAll('.alert').forEach(el => {
    setTimeout(() => {
        el.style.opacity = '0';
        el.style.transition = 'opacity .4s';
        setTimeout(() => el.remove(), 400);
    }, 5000);
});
</script>

@stack('scripts')
</body>
</html>