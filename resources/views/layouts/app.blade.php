<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quiz App</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; background:#f8fafc; color:#111827; padding:20px; }
        header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
        nav a { margin-right:12px; color:#2563eb; text-decoration:none; }
        .container { max-width:900px; margin:0 auto; }
        button { background:#2563eb; color:white; border:none; padding:8px 12px; border-radius:6px; cursor:pointer; }
        input[type=radio] { margin-right:6px; }
    </style>
</head>
<body>
    <header>
        <div><a href="{{ route('landing') }}">Quiz Portal</a></div>
        <nav>
            @auth
                <span>{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
                <a href="{{ route(auth()->user()->role === 'questioner' ? 'dashboard.questioner' : 'dashboard.user') }}">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endauth
        </nav>
    </header>

    <div class="container">
        @yield('content')
    </div>
</body>
</html>
