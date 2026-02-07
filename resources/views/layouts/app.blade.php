<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'MyRent') }} - @yield('title')</title>
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('storage/documents/image/logo motor.jpg') }}">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #64748b;
            --accent: #f59e0b;
            --background: #f8fafc;
            --card: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --success: #10b981;
            --danger: #ef4444;
            --sidebar-width: 260px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--background);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
            padding: 0.75rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-link {
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: var(--primary);
        }

        .btn {
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        /* Sidebar & Content Layout */
        .app-container {
            display: flex;
            flex: 1;
        }

        .sidebar {
            width: var(--sidebar-width);
            background: white;
            border-right: 1px solid var(--border);
            padding: 2rem 1rem;
            display: none; /* Hidden by default, shown via JS if needed */
            flex-direction: column;
            gap: 0.5rem;
            position: sticky;
            top: 64px;
            height: calc(100vh - 64px);
        }

        .sidebar.active {
            display: flex;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 500;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .sidebar-item:hover, .sidebar-item.active {
            background: #eff6ff;
            color: var(--primary);
        }

        .sidebar-item i {
            width: 20px;
            text-align: center;
        }

        .main-content {
            flex: 1;
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        .card {
            background: var(--card);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid var(--border);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        @yield('styles')
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/" class="logo">
            <img src="{{ asset('storage/documents/image/logo motor.jpg') }}" alt="Logo" style="height: 40px; width: auto; border-radius: 8px;">
            MyRent
        </a>
        <div class="nav-links" id="navLinks">
            <!-- Guest Links -->
            <div id="guestLinks">
                <a href="/login" class="nav-link">Login</a>
                <a href="/register" class="btn btn-primary">Daftar Sekarang</a>
            </div>
            <!-- Auth Links -->
            <div id="authLinks" style="display: none;">
                <button onclick="handleLogout()" class="btn" style="border: 1.5px solid var(--danger); color: var(--danger); background: transparent; padding: 0.4rem 1rem; font-size: 0.875rem;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </div>
        </div>
    </nav>

    <div class="app-container">
        <aside class="sidebar" id="appSidebar">
            @yield('sidebar')
        </aside>
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const token = localStorage.getItem('token');
            const user = JSON.parse(localStorage.getItem('user'));
            const guestLinks = document.getElementById('guestLinks');
            const authLinks = document.getElementById('authLinks');
            const sidebar = document.getElementById('appSidebar');

            if (token && user) {
                guestLinks.style.display = 'none';
                authLinks.style.display = 'flex';
                
                // Show sidebar if the page defines it
                if (document.querySelector('[data-has-sidebar]')) {
                    sidebar.classList.add('active');
                }
            } else {
                guestLinks.style.display = 'flex';
                authLinks.style.display = 'none';
            }
        });

        function handleLogout() {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            window.location.href = '/login';
        }
    </script>
    @yield('scripts')
</body>
</html>
