<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Tochka Free Market')</title>
    
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }
        .header .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }
        .header .nav-links {
            display: flex;
            gap: 2rem;
        }
        .header .nav-links a {
            color: white;
            text-decoration: none;
        }
        .header .nav-links a:hover { opacity: 0.8; }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        .card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            text-decoration: none;
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
        }
        .btn:hover { background-color: #2980b9; }
        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 2rem;
            text-align: center;
            margin-top: 4rem;
        }
        .item-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        .item-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .item-card .content { padding: 1rem; }
        .item-card h3 { margin: 0 0 0.5rem 0; }
        .item-card .price {
            color: #27ae60;
            font-weight: bold;
            font-size: 1.2rem;
        }
        .item-card .vendor { color: #7f8c8d; font-size: 0.9rem; }
    </style>
</head>
<body>
    <header class="header">
        <nav>
            <a href="{{ route('home') }}" class="logo">🔐 Tochka Market</a>
            <div class="nav-links">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('items.index') }}">Browse</a>
                <a href="{{ route('vendors.index') }}">Vendors</a>
            </div>
        </nav>
    </header>

    <main class="container">
        @yield('content')
    </main>

    <footer class="footer">
        <p>&copy; {{ date('Y') }} Tochka Free Market</p>
        <p>Secure • Decentralized • Anonymous</p>
    </footer>
</body>
</html>
