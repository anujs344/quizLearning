<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Quiz</title>
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
            margin-bottom:18px;
        }
        form { display:flex; flex-direction:column; gap:14px; max-width:700px; }
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
        .row { display:flex; gap:14px; }
        .row > div { flex:1; }
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
        .link {
            font-size:12px;
            color:#bfdbfe;
            text-decoration:none;
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
        .secondary-card {
            margin-top:26px;
            padding:14px 14px 12px;
            border-radius:16px;
            background:rgba(15,23,42,0.96);
            border:1px solid rgba(55,65,81,0.9);
            font-size:13px;
        }
        .secondary-card h3 {
            margin:0 0 6px;
            font-size:14px;
        }
        .btn-small {
            display:inline-flex;
            align-items:center;
            justify-content:center;
            border-radius:999px;
            padding:7px 14px;
            border:1px solid #4b5563;
            background:rgba(15,23,42,0.9);
            color:#e5e7eb;
            font-size:12px;
            text-decoration:none;
        }
        ul {
            padding-left:18px;
            margin:6px 0 0;
            font-size:12px;
            color:#9ca3af;
        }
        li { margin-bottom:4px; }
    </style>
</head>
<body>
    <header>
        <div class="brand">Edit quiz</div>
        <a href="{{ route('quizzes.index') }}" class="link">← Back to quizzes</a>
    </header>
    <main>
        <div class="headline">{{ $quiz->title }}</div>
        <div class="subtext">
            Update quiz details and then manage its questions. Each attempt will use {{ $quiz->total_questions }} random questions.
        </div>

        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('quizzes.update', $quiz) }}">
            @csrf
            @method('PUT')
            <div>
                <label for="title">Title</label>
                <input id="title" type="text" name="title" value="{{ old('title', $quiz->title) }}" required>
            </div>
            <div>
                <label for="description">Description</label>
                <textarea id="description" name="description">{{ old('description', $quiz->description) }}</textarea>
            </div>
            <div class="row">
                <div>
                    <label for="total_questions">Total questions per attempt</label>
                    <input id="total_questions" type="number" name="total_questions" value="{{ old('total_questions', $quiz->total_questions) }}" min="1" required>
                </div>
                <div>
                    <label>Active</label>
                    <label style="display:flex;align-items:center;gap:6px;margin-top:6px;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $quiz->is_active) ? 'checked' : '' }}>
                        <span style="font-size:12px;">Quiz is visible to users</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn-primary">Save changes</button>
        </form>

        <div class="secondary-card">
            <h3>Manage questions for this quiz</h3>
            <p style="margin:0 0 6px;font-size:13px;">
                Add MCQs, configure timers, skipping/back rules, and range‑based scoring for each question.
            </p>
            <a href="{{ route('quizzes.questions.create', $quiz) }}" class="btn-small">+ Add question</a>
            <ul>
                <li>Each question can have multiple options (A, B, C, D, ...).</li>
                <li>You can define whether skipping is allowed, time limit, and whether users can go back.</li>
                <li>Set base points and optional value ranges (e.g. 0–100 ➜ 20 pts, 100–500 ➜ 30 pts).</li>
            </ul>
        </div>
    </main>
</body>
</html>


