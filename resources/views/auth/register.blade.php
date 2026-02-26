<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Quiz Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: radial-gradient(circle at top left, #1e293b, #020617);
            color: #e5e7eb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .shell { width: 100%; max-width: 460px; padding: 24px; }
        .card {
            background: rgba(15,23,42,0.96);
            border-radius: 20px;
            padding: 26px 22px 22px;
            box-shadow: 0 24px 80px rgba(15,23,42,0.9);
            border: 1px solid rgba(148,163,184,0.28);
        }
        .title {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .subtitle {
            font-size: 13px;
            color: #9ca3af;
            margin-bottom: 16px;
        }
        form { display: flex; flex-direction: column; gap: 12px; margin-top: 4px; }
        label {
            font-size: 12px;
            color: #e5e7eb;
            margin-bottom: 4px;
            display: block;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 9px 10px;
            border-radius: 10px;
            border: 1px solid #4b5563;
            background: #020617;
            color: #e5e7eb;
            font-size: 13px;
        }
        input:focus,
        select:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 1px rgba(99,102,241,0.6);
        }
        .hint {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 2px;
        }
        .btn-primary {
            width: 100%;
            border: none;
            border-radius: 999px;
            padding: 9px 0;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: white;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            box-shadow: 0 12px 35px rgba(79,70,229,0.7);
            margin-top: 4px;
        }
        .btn-primary:hover { filter: brightness(1.05); }
        .error {
            margin-top: 8px;
            padding: 8px 10px;
            border-radius: 10px;
            background: rgba(220,38,38,0.12);
            border: 1px solid rgba(248,113,113,0.6);
            font-size: 12px;
            color: #fecaca;
        }
        .footer {
            margin-top: 14px;
            font-size: 12px;
            color: #9ca3af;
            text-align: center;
        }
        .footer a { color: #bfdbfe; text-decoration: none; }
    </style>
</head>
<body>
    <div class="shell">
        <div class="card">
            <div class="title">Create your account</div>
            <div class="subtitle">
                Choose whether you’re a <strong>Questioner</strong> (create quizzes) or a <strong>User</strong> (take quizzes).
            </div>

            @if ($errors->any())
                <div class="error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}">
                @csrf
                <div>
                    <label for="name">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
                </div>
                <div>
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                </div>
                <div>
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="">Select role</option>
                        <option value="questioner" {{ old('role', 'user') === 'questioner' ? 'selected' : '' }}>Questioner (create quizzes)</option>
                        <option value="user" {{ old('role', 'user') === 'user' ? 'selected' : '' }}>User (take quizzes)</option>
                    </select>
                    <div class="hint">
                        You can create separate accounts for questioners and users if needed.
                    </div>
                </div>
                <div>
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                </div>
                <div>
                    <label for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn-primary">Sign up</button>
            </form>

            <div class="footer">
                Already have an account?
                <a href="{{ route('login') }}">Login</a>
            </div>
        </div>
    </div>
</body>
</html>


