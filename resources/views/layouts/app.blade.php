<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SaaS POS' }}</title>

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Custom Style --}}
    <style>
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            background-color: var(--bs-body-bg);
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--bs-body-bg);
            border-right: 1px solid var(--bs-border-color);
            transition: all 0.3s ease;
        }
        .sidebar.collapsed {
            width: 75px;
        }
        .sidebar .nav-link {
            color: var(--bs-body-color);
            border-radius: 10px;
            margin: 4px 0;
        }
        .sidebar .nav-link.active {
            background-color: var(--bs-primary-bg-subtle);
            color: var(--bs-primary);
            font-weight: 600;
        }
        .sidebar .nav-link:hover {
            background-color: var(--bs-secondary-bg);
            color: var(--bs-primary);
        }
        .sidebar.collapsed .nav-link span {
            display: none;
        }
        .sidebar.collapsed .nav-link i {
            margin-right: 0;
            text-align: center;
            width: 100%;
        }
        .sidebar-logo {
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--bs-primary);
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
            padding: 20px;
        }
        .sidebar.collapsed ~ .main-content {
            margin-left: 75px;
        }

        /* Navbar */
        .navbar {
            background-color: var(--bs-body-bg);
            border-bottom: 1px solid var(--bs-border-color);
            position: sticky;
            top: 0;
            z-index: 999;
        }
    </style>

    @stack('head')
</head>
<body>
    {{-- Sidebar --}}
    @include('layouts.sidebar')

    {{-- Main Content --}}
    <div class="main-content">
        {{-- Navbar --}}
        @include('layouts.navbar')

        {{-- Page Content --}}
        <main>
            @yield('content')
        </main>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Sidebar & Dark Mode Script --}}
    <script>
        const sidebar = document.getElementById('sidebar');
        const collapseBtn = document.getElementById('collapseBtn');
        const toggleSidebar = document.getElementById('toggleSidebar');

        // Toggle Sidebar
        collapseBtn?.addEventListener('click', () => sidebar.classList.toggle('collapsed'));
        toggleSidebar?.addEventListener('click', () => sidebar.classList.toggle('collapsed'));

        // Dark Mode
        const darkToggle = document.getElementById('darkModeToggle');
        const html = document.documentElement;
        const savedTheme = localStorage.getItem('theme');

        if (savedTheme === 'dark') {
            html.setAttribute('data-bs-theme', 'dark');
            if (darkToggle) darkToggle.checked = true;
        }

        darkToggle?.addEventListener('change', () => {
            if (darkToggle.checked) {
                html.setAttribute('data-bs-theme', 'dark');
                localStorage.setItem('theme', 'dark');
            } else {
                html.setAttribute('data-bs-theme', 'light');
                localStorage.setItem('theme', 'light');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
