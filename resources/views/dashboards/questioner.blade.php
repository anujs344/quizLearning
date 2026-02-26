<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questioner Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: radial-gradient(circle at top left, #1f2937, #020617);
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
            border:1px solid rgba(96,165,250,0.7);
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
        .actions {
            display:flex;
            gap:10px;
            margin-bottom:18px;
        }
        .btn {
            border-radius:999px;
            padding:8px 16px;
            font-size:13px;
            border:1px solid transparent;
            cursor:pointer;
            text-decoration:none;
            display:inline-flex;
            align-items:center;
            gap:6px;
        }
        .btn-primary {
            background:linear-gradient(135deg,#4f46e5,#6366f1);
            color:white;
            box-shadow:0 12px 30px rgba(79,70,229,0.7);
        }
        .btn-outline {
            background:rgba(15,23,42,0.9);
            color:#e5e7eb;
            border-color:#4b5563;
        }
        table {
            width:100%;
            border-collapse:collapse;
            margin-top:8px;
            font-size:13px;
        }
        th, td {
            padding:8px 10px;
            border-bottom:1px solid rgba(31,41,55,0.9);
        }
        th {
            text-align:left;
            font-weight:500;
            color:#9ca3af;
            font-size:12px;
        }
        tbody tr:hover {
            background:rgba(15,23,42,0.95);
        }
        .pill {
            font-size:11px;
            padding:3px 8px;
            border-radius:999px;
            border:1px solid rgba(148,163,184,0.7);
        }
        .layout-row {
            display:flex;
            gap:18px;
            align-items:flex-start;
            margin-top:12px;
        }
        .panel { flex:3; }
        .side-panel {
            flex:2;
            background:rgba(15,23,42,0.96);
            border-radius:16px;
            padding:12px 13px;
            border:1px solid rgba(55,65,81,0.9);
            font-size:12px;
            color:#9ca3af;
        }
        .side-panel h3 {
            font-size:13px;
            margin-top:0;
            margin-bottom:6px;
            color:#e5e7eb;
        }
        .side-panel ul {
            padding-left:18px;
            margin:6px 0;
        }
        .side-panel li {
            margin-bottom:4px;
        }
        .logout-form {
            margin:0;
        }
        .logout-form button {
            background:none;
            border:none;
            color:#9ca3af;
            font-size:12px;
            cursor:pointer;
        }
    </style>
</head>
<body>
    <header>
        <div>
            <div class="brand">Quiz Portal</div>
            <div style="font-size:11px;color:#9ca3af;">Questioner dashboard</div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
            <span class="role-tag">{{ $user->name }} · Questioner</span>
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </header>
    <main>
        <div class="headline">Your quizzes</div>
        <div class="subtext">
            Create and manage MCQ quizzes with per‑question timers, skip rules, and flexible scoring.
        </div>
        <div class="actions">
            <a href="{{ route('quizzes.create') }}" class="btn btn-primary">+ New quiz</a>
            <a href="{{ route('quizzes.index') }}" class="btn btn-outline">View all quizzes</a>
        </div>

        <div class="layout-row">
            <div class="panel">
                {{-- Basic placeholder table; real data will come from QuizController --}}
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Questions</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Example quiz</td>
                            <td>30</td>
                            <td><span class="pill">Sample only</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="side-panel">
                <h3>What you can configure</h3>
                <ul>
                    <li>Per‑question timer (e.g. 30s, 60s, 120s)</li>
                    <li>Allow or block skipping, and show/hide skip button</li>
                    <li>Allow or prevent going back to previous questions</li>
                    <li>Set base points and value ranges (0–100 ➜ 20 pts, 100–500 ➜ 30 pts)</li>
                    <li>Automatically pick 30 random questions per attempt</li>
                </ul>
            </div>
        </div>
    </main>
</body>
</html>


