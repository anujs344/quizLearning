<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Quizzes</title>
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
        main {
            padding:20px 24px 24px;
            max-width:900px;
            margin:0 auto;
        }
        .headline {
            font-size:20px;
            font-weight:600;
            margin-bottom:4px;
        }
        .subtext {
            font-size:13px;
            color:#9ca3af;
            margin-bottom:16px;
        }
        .btn-primary {
            display:inline-flex;
            align-items:center;
            gap:6px;
            border-radius:999px;
            padding:8px 14px;
            border:none;
            background:linear-gradient(135deg,#4f46e5,#6366f1);
            color:white;
            font-size:13px;
            text-decoration:none;
            box-shadow:0 12px 30px rgba(79,70,229,0.7);
        }
        table {
            width:100%;
            border-collapse:collapse;
            margin-top:14px;
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
        .actions a, .actions form {
            display:inline-block;
            margin-right:6px;
        }
        .link {
            font-size:12px;
            color:#bfdbfe;
            text-decoration:none;
        }
        .status {
            margin-bottom:10px;
            padding:8px 10px;
            border-radius:10px;
            background:rgba(22,163,74,0.12);
            border:1px solid rgba(34,197,94,0.6);
            font-size:12px;
            color:#bbf7d0;
        }
    </style>
</head>
<body>
    <header>
        <div class="brand">Your quizzes</div>
        <a href="{{ route('dashboard.questioner') }}" class="link">← Back to dashboard</a>
    </header>
    <main>
        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        <div class="headline">Quiz list</div>
        <div class="subtext">
            Manage all quizzes you’ve created. From here you can edit quiz settings and later attach questions.
        </div>
        <a href="{{ route('quizzes.create') }}" class="btn-primary">+ New quiz</a>

        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Total questions</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($quizzes as $quiz)
                    <tr>
                        <td>{{ $quiz->title }}</td>
                        <td>{{ $quiz->total_questions }}</td>
                        <td>
                            <span class="pill">{{ $quiz->is_active ? 'Active' : 'Inactive' }}</span>
                        </td>
                        <td class="actions">
                            <a href="{{ route('quizzes.show', $quiz) }}" class="link">View</a>
                            <a href="{{ route('quizzes.edit', $quiz) }}" class="link">Edit</a>
                            <form method="POST" action="{{ route('quizzes.destroy', $quiz) }}" onsubmit="return confirm('Delete this quiz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="link" style="background:none;border:none;padding:0;">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">You haven’t created any quizzes yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </main>
</body>
</html>


