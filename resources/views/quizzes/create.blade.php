<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz</title>
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
            max-width:700px;
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
            margin-bottom:18px;
        }
        form { display:flex; flex-direction:column; gap:14px; }
        label {
            font-size:12px;
            color:#e5e7eb;
            margin-bottom:4px;
            display:block;
        }
        input[type="text"],
        input[type="number"],
        textarea {
            width:100%;
            padding:9px 10px;
            border-radius:10px;
            border:1px solid #4b5563;
            background:#020617;
            color:#e5e7eb;
            font-size:13px;
        }
        textarea { min-height:80px; resize:vertical; }
        input:focus, textarea:focus {
            outline:none;
            border-color:#6366f1;
            box-shadow:0 0 0 1px rgba(99,102,241,0.6);
        }
        .row {
            display:flex;
            gap:14px;
        }
        .row > div { flex:1; }
        .hint {
            font-size:11px;
            color:#9ca3af;
            margin-top:2px;
        }
        .btn-primary {
            border-radius:999px;
            padding:9px 18px;
            border:none;
            background:linear-gradient(135deg,#4f46e5,#6366f1);
            color:white;
            font-size:13px;
            font-weight:600;
            cursor:pointer;
            box-shadow:0 12px 30px rgba(79,70,229,0.7);
            margin-top:6px;
        }
        .error {
            margin-bottom:10px;
            padding:8px 10px;
            border-radius:10px;
            background:rgba(220,38,38,0.12);
            border:1px solid rgba(248,113,113,0.6);
            font-size:12px;
            color:#fecaca;
        }
        .link {
            font-size:12px;
            color:#bfdbfe;
            text-decoration:none;
        }
    </style>
</head>
<body>
    <header>
        <div class="brand">Create quiz</div>
        <a href="{{ route('quizzes.index') }}" class="link">← Back to quizzes</a>
    </header>
    <main>
        <div class="headline">New quiz</div>
        <div class="subtext">
            Define the basic settings for this quiz. Each attempt will use {{ old('total_questions', 30) }} random questions by default.
        </div>

        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('quizzes.store') }}">
            @csrf
            <div>
                <label for="title">Title</label>
                <input id="title" type="text" name="title" value="{{ old('title') }}" required>
            </div>
            <div>
                <label for="description">Description</label>
                <textarea id="description" name="description">{{ old('description') }}</textarea>
                <div class="hint">Short description that users see before starting the quiz.</div>
            </div>
            <div class="row">
                <div>
                    <label for="total_questions">Total questions per attempt</label>
                    <input id="total_questions" type="number" name="total_questions" value="{{ old('total_questions', 30) }}" min="1" required>
                    <div class="hint">For your use case, set this to 30.</div>
                </div>
                <div>
                    <label>Active</label>
                    <label style="display:flex;align-items:center;gap:6px;margin-top:6px;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <span style="font-size:12px;">Quiz is visible to users</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn-primary">Save quiz</button>
        </form>
    </main>
</body>
</html>


