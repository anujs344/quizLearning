<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: radial-gradient(circle at top left, #0f172a, #020617);
            color: #e5e7eb;
            min-height: 100vh;
        }
        header {
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:14px 24px;
            border-bottom:1px solid rgba(55,65,81,0.7);
            background:rgba(15,23,42,0.96);
        }
        .brand { font-weight:600; font-size:16px; }
        .role-tag {
            font-size:11px;
            padding:4px 10px;
            border-radius:999px;
            border:1px solid rgba(34,197,94,0.7);
            background:rgba(15,23,42,0.9);
        }
        main {
            padding:20px 24px 24px;
            max-width:1100px;
            margin:0 auto;
        }
        .headline {
            font-size:22px;
            font-weight:600;
            margin-bottom:4px;
        }
        .subtext {
            font-size:13px;
            color:#9ca3af;
            margin-bottom:18px;
        }
        .card {
            background:rgba(15,23,42,0.96);
            border-radius:18px;
            padding:16px 16px 14px;
            border:1px solid rgba(55,65,81,0.9);
            display:flex;
            justify-content:space-between;
            align-items:center;
        }
        .btn-primary {
            border-radius:999px;
            padding:9px 18px;
            font-size:13px;
            border:none;
            cursor:pointer;
            background:linear-gradient(135deg,#22c55e,#16a34a);
            color:white;
            box-shadow:0 12px 30px rgba(22,163,74,0.7);
            text-decoration:none;
            display:inline-flex;
            align-items:center;
            gap:6px;
        }
        .logout-form { margin:0; }
        .logout-form button {
            background:none;
            border:none;
            color:#9ca3af;
            font-size:12px;
            cursor:pointer;
        }
        ul {
            margin:8px 0 0;
            padding-left:18px;
            font-size:12px;
            color:#9ca3af;
        }
        li { margin-bottom:4px; }
    </style>
</head>
<body>
    <header>
        <div>
            <div class="brand">Quiz Portal</div>
            <div style="font-size:11px;color:#9ca3af;">User dashboard</div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
            <span class="role-tag">{{ $user->name }} · User</span>
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </header>
    <main>
        <div style="display:flex;gap:18px;align-items:flex-start;flex-wrap:wrap;">
            <div style="flex:1;min-width:300px;">
                <div class="card" style="display:block;">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="width:64px;height:64px;border-radius:12px;background:linear-gradient(135deg,#60a5fa,#3b82f6);display:flex;align-items:center;justify-content:center;font-weight:700;color:white;font-size:20px;">{{ strtoupper(substr($user->name,0,1)) }}</div>
                        <div>
                            <div style="font-size:16px;font-weight:600;color:#e5e7eb;">{{ $user->name }}</div>
                            <div style="font-size:13px;color:#9ca3af;">{{ $user->email }}</div>
                        </div>
                    </div>
                    <div style="margin-top:12px;display:flex;gap:12px;">
                        <div style="flex:1;padding:10px;border-radius:10px;background:rgba(255,255,255,0.03);text-align:center;">
                            <div style="font-size:12px;color:#9ca3af">Attempts</div>
                            <div style="font-size:20px;font-weight:700">{{ $totalAttempts ?? 0 }}</div>
                        </div>
                        <div style="flex:1;padding:10px;border-radius:10px;background:rgba(255,255,255,0.03);text-align:center;">
                            <div style="font-size:12px;color:#9ca3af">Best score</div>
                            <div style="font-size:20px;font-weight:700">{{ $bestScore ?? 0 }}</div>
                        </div>
                    </div>
                    <div style="margin-top:12px;">
                        <a href="{{ route('user.quizzes.index') }}" class="btn-primary">View &amp; start quizzes</a>
                    </div>
                </div>
            </div>

            <div style="flex:1.4;min-width:320px;">
                <div style="font-size:16px;font-weight:600;margin-bottom:8px;color:#e5e7eb;">Recent attempts</div>
                @if(!empty($attempts) && $attempts->count() > 0)
                    <div style="display:grid;gap:10px;">
                        @foreach($attempts as $a)
                            <div style="background:rgba(15,23,42,0.9);padding:12px;border-radius:10px;border:1px solid rgba(55,65,81,0.9);display:flex;justify-content:space-between;align-items:center;">
                                <div>
                                    <div style="font-weight:600;color:#e5e7eb;">{{ $a->quiz->title }}</div>
                                    <div style="font-size:12px;color:#9ca3af;">{{ $a->created_at->format('Y-m-d H:i') }}</div>
                                </div>
                                <div style="text-align:right;">
                                    <div style="font-weight:700;font-size:18px;color:#a3e635;">{{ $a->total_score }}</div>
                                    <div style="font-size:12px;color:#9ca3af;">Score</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="color:#9ca3af">You have not attempted any quizzes yet. Click "View & start quizzes" to begin.</div>
                @endif
            </div>
        </div>
    </main>
</body>
</html>


