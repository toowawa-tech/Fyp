<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KB Piling Materials and Asset Tracker</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            background-color: #f4f6f9;
            min-height: 100vh;
            color: #333;
        }

        /* Fixed Dark Left Sidebar */
        .sidebar {
            width: 260px;
            background-color: #1e2530;
            color: #fff;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 20px 15px;
            background-color: #181e28;
            font-size: 0.95rem;
            font-weight: bold;
            border-bottom: 1px solid #2d3748;
            line-height: 1.4;
            color: #ffffff;
            letter-spacing: 0.5px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 15px 0;
            margin: 0;
        }

        .menu-category {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #a0aec0;
            padding: 10px 20px 5px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #cbd5e0;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background 0.2s, color 0.2s;
        }

        .sidebar-menu li a:hover, .sidebar-menu li.active a {
            background-color: #2d3748;
            color: #ffffff;
            border-left: 4px solid #e91e63;
        }

        /* Main Content Area */
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 20px 30px;
            display: flex;
            flex-direction: column;
        }

        /* Top Navigation Header */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .breadcrumbs {
            font-size: 0.85rem;
            color: #718096;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #1a202c;
        }

        .btn-logout {
            background: none;
            border: none;
            color: #4a5568;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .btn-logout:hover {
            color: #e53e3e;
        }

        /* Custom Red Header for Section Cards */
        .card-section-header {
            background: linear-gradient(135deg, #e91e63, #d81b60);
            color: white;
            padding: 12px 20px;
            border-radius: 8px 8px 0 0;
            font-weight: bold;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-body-custom {
            background: white;
            padding: 20px;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }
    </style>
</head>
<body>

<!-- Left Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        KB PILING MATERIALS AND ASSET TRACKER
    </div>

    <ul class="sidebar-menu">
        @if(strtolower(auth()->user()->role) === 'admin')
            <div class="menu-category">ADMINISTRATION</div>
            <li class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                <a href="/admin/users">👥 User Management</a>
            </li>
            <li class="{{ request()->is('admin/audit-trail*') ? 'active' : '' }}">
                <a href="/admin/audit-trail">📜 Audit Trail Log</a>
            </li>
            <li class="{{ request()->is('materials*') ? 'active' : '' }}">
                <a href="/materials">📦 Material & Stock List</a>
            </li>

        @elseif(strtolower(auth()->user()->role) === 'storekeeper')
            <div class="menu-category">STOREKEEPER</div>
            <li class="{{ request()->is('storekeeper/dashboard*') ? 'active' : '' }}">
                <a href="/storekeeper/dashboard">📊 Storekeeper Dashboard</a>
            </li>
            <li class="{{ request()->is('materials*') ? 'active' : '' }}">
                <a href="/materials">📦 Material Stock Management</a>
            </li>
            <li class="{{ request()->is('storekeeper/requests*') ? 'active' : '' }}">
                <a href="/storekeeper/requests">📋 Review & Approval Requests</a>
            </li>

        @elseif(in_array(strtolower(auth()->user()->role), ['manager', 'site manager']))
            <div class="menu-category">SITE MANAGER</div>
            <li class="{{ request()->is('manager/dashboard*') ? 'active' : '' }}">
                <a href="/manager/dashboard">📊 Manager Dashboard</a>
            </li>
            <li class="{{ request()->is('manager/requests*') ? 'active' : '' }}">
                <a href="/manager/requests">📝 Site Material Request</a>
            </li>
        @endif
    </ul>
</div>

<!-- Main Content Area -->
<div class="main-content">
    <!-- Top Header -->
    <div class="top-header">
        <div>
            <div class="breadcrumbs">Pages / @yield('breadcrumb', 'Dashboard')</div>
            <div class="page-title">@yield('title', 'Dashboard')</div>
        </div>
        <div>
            <form action="/logout" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-logout">Sign Out</button>
            </form>
        </div>
    </div>

    <!-- Main Page Content -->
    @yield('content')
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>