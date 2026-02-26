<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Question</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: radial-gradient(circle at top left, #020617, #020617);
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
        form { display:flex; flex-direction:column; gap:16px; }
        label {
            font-size:12px;
            color:#e5e7eb;
            margin-bottom:4px;
            display:block;
        }
        textarea,
        input[type="text"],
        input[type="number"] {
            width:100%;
            padding:9px 10px;
            border-radius:10px;
            border:1px solid #4b5563;
            background:#020617;
            color:#e5e7eb;
            font-size:13px;
        }
        textarea { min-height:80px; resize:vertical; }
        textarea:focus, input:focus {
            outline:none;
            border-color:#6366f1;
            box-shadow:0 0 0 1px rgba(99,102,241,0.6);
        }
        .row { display:flex; gap:14px; }
        .row > div { flex:1; }
        fieldset {
            border-radius:14px;
            border:1px solid rgba(55,65,81,0.9);
            padding:12px 12px 10px;
        }
        legend {
            font-size:12px;
            color:#9ca3af;
            padding:0 6px;
        }
        .hint {
            font-size:11px;
            color:#9ca3af;
            margin-top:2px;
        }
        .option-row {
            display:flex;
            gap:10px;
            align-items:center;
            margin-bottom:8px;
        }
        .option-row input[type="checkbox"] {
            width:14px;
            height:14px;
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
    </style>
</head>
<body>
    <header>
        <div class="brand">Add question · {{ $quiz->title }}</div>
        <a href="{{ route('quizzes.edit', $quiz) }}" class="link">← Back to quiz</a>
    </header>
    <main>
        <div class="headline">New MCQ</div>
        <div class="subtext">
            Define the question text, timer, skipping/back rules, and answer options. You can also define range‑based scoring.
        </div>

        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('quizzes.questions.store', $quiz) }}">
            @csrf

            <div>
                <label for="text">Question text</label>
                <textarea id="text" name="text" required>{{ old('text') }}</textarea>
            </div>

            <div class="row">
                <div>
                    <label for="time_limit_seconds">Time limit (seconds)</label>
                    <input id="time_limit_seconds" type="number" name="time_limit_seconds" value="{{ old('time_limit_seconds', 60) }}" min="5" required>
                    <div class="hint">How long the user gets for this question.</div>
                </div>
                <div>
                    <label for="base_points">Base points</label>
                    <input id="base_points" type="number" name="base_points" value="{{ old('base_points', 1) }}" min="0" required>
                    <div class="hint">Base points if answered correctly (before range rules).</div>
                </div>
            </div>

            <fieldset>
                <legend>Navigation &amp; skipping</legend>
                <div class="row">
                    <label style="display:flex;align-items:center;gap:6px;">
                        <input type="checkbox" name="allow_skip" value="1" {{ old('allow_skip', true) ? 'checked' : '' }}>
                        <span>Allow skipping this question</span>
                    </label>
                    <label style="display:flex;align-items:center;gap:6px;">
                        <input type="checkbox" name="show_skip_button" value="1" {{ old('show_skip_button', true) ? 'checked' : '' }}>
                        <span>Show skip button</span>
                    </label>
                    <label style="display:flex;align-items:center;gap:6px;">
                        <input type="checkbox" name="allow_back" value="1" {{ old('allow_back', false) ? 'checked' : '' }}>
                        <span>Allow going back from later questions</span>
                    </label>
                </div>
            </fieldset>

            <fieldset>
                <legend>Options</legend>
                <div class="hint" style="margin-bottom:6px;">Define at least two options. Mark the ones that are correct.</div>

                @php
                    $defaultOptions = old('options', [
                        ['label' => 'A', 'text' => '', 'points' => 0, 'is_correct' => false],
                        ['label' => 'B', 'text' => '', 'points' => 0, 'is_correct' => false],
                        ['label' => 'C', 'text' => '', 'points' => 0, 'is_correct' => false],
                        ['label' => 'D', 'text' => '', 'points' => 0, 'is_correct' => false],
                    ]);
                @endphp

                @foreach ($defaultOptions as $index => $opt)
                    <div class="option-row">
                        <input type="text" name="options[{{ $index }}][label]" value="{{ $opt['label'] }}" style="width:50px;" required>
                        <input type="text" name="options[{{ $index }}][text]" placeholder="Option text (e.g. 210)" value="{{ $opt['text'] }}" required>
                        <input type="number" name="options[{{ $index }}][points]" placeholder="Points (default 0)" value="{{ $opt['points'] }}" style="width:120px;">
                        <label style="display:flex;align-items:center;gap:4px;font-size:12px;">
                            <input type="checkbox" name="options[{{ $index }}][is_correct]" value="1" {{ !empty($opt['is_correct']) ? 'checked' : '' }}>
                            Correct
                        </label>
                    </div>
                @endforeach
            </fieldset>

            <fieldset>
                <legend>Range‑based scoring (optional)</legend>
                <div class="hint" style="margin-bottom:6px;">
                    Example: if selected option value is between 0–100 ➜ 20 points, 100–500 ➜ 30 points.
                </div>
                @php
                    // Show 4 empty rule rows (one per option) so questioners can configure four ranges if desired
                    $defaultRules = old('rules', [
                        ['min_value' => '', 'max_value' => '', 'points' => ''],
                        ['min_value' => '', 'max_value' => '', 'points' => ''],
                        ['min_value' => '', 'max_value' => '', 'points' => ''],
                        ['min_value' => '', 'max_value' => '', 'points' => ''],
                    ]);
                @endphp
                @foreach ($defaultRules as $i => $rule)
                    <div class="row" style="margin-bottom:6px;">
                        <div>
                            <label>Min value</label>
                            <input type="number" step="0.01" name="rules[{{ $i }}][min_value]" value="{{ $rule['min_value'] }}">
                        </div>
                        <div>
                            <label>Max value</label>
                            <input type="number" step="0.01" name="rules[{{ $i }}][max_value]" value="{{ $rule['max_value'] }}">
                        </div>
                        <div>
                            <label>Points</label>
                            <input type="number" name="rules[{{ $i }}][points]" value="{{ $rule['points'] }}">
                        </div>
                    </div>
                @endforeach
                <div class="hint">Leave min/max empty to ignore a row.</div>
            </fieldset>

            <button type="submit" class="btn-primary">Save question</button>
        </form>
    </main>
</body>
</html>


