<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }
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
        .shell {
            width: 100%;
            max-width: 1000px;
            padding: 24px;
        }
        .card {
            background: rgba(15,23,42,0.96);
            border-radius: 24px;
            padding: 32px 28px;
            box-shadow: 0 24px 80px rgba(15,23,42,0.8);
            border: 1px solid rgba(148,163,184,0.18);
            display: grid;
            grid-template-columns: minmax(0, 3fr) minmax(0, 2.3fr);
            gap: 28px;
        }
        @media (max-width: 800px) {
            .card {
                grid-template-columns: minmax(0,1fr);
            }
        }
        .title {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.03em;
            margin-bottom: 8px;
        }
        .subtitle {
            color: #9ca3af;
            font-size: 14px;
            margin-bottom: 24px;
        }
        .pill-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 24px;
        }
        .pill {
            padding: 6px 12px;
            border-radius: 999px;
            border: 1px solid rgba(148,163,184,0.4);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #e5e7eb;
            background: radial-gradient(circle at top left, rgba(59,130,246,0.24), transparent);
        }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0,1fr));
            gap: 14px;
            font-size: 13px;
        }
        @media (max-width: 800px) {
            .feature-grid {
                grid-template-columns: minmax(0,1fr);
            }
        }
        .feature {
            padding: 10px 12px;
            border-radius: 12px;
            background: rgba(15,23,42,0.9);
            border: 1px solid rgba(75,85,99,0.7);
        }
        .feature-title {
            font-weight: 600;
            font-size: 12px;
            margin-bottom: 4px;
            color: #e5e7eb;
        }
        .feature-body {
            color: #9ca3af;
            font-size: 12px;
        }
        .auth-card {
            background: radial-gradient(circle at top, rgba(37,99,235,0.26), rgba(15,23,42,0.95));
            border-radius: 18px;
            padding: 22px 20px 20px;
            border: 1px solid rgba(96,165,250,0.6);
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .auth-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4px;
        }
        .auth-title {
            font-size: 16px;
            font-weight: 600;
        }
        .badge {
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 999px;
            background: rgba(15,23,42,0.92);
            border: 1px solid rgba(148,163,184,0.7);
            color: #e5e7eb;
        }
        .auth-switch {
            display: grid;
            grid-template-columns: repeat(2, minmax(0,1fr));
            border-radius: 999px;
            background: rgba(15,23,42,0.8);
            padding: 3px;
            gap: 2px;
            font-size: 12px;
        }
        .auth-switch a {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            padding: 8px 0;
            border-radius: 999px;
            text-decoration: none;
            color: #e5e7eb;
            border: 1px solid transparent;
        }
        .auth-switch a.primary {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            box-shadow: 0 10px 30px rgba(79,70,229,0.6);
        }
        .auth-switch a.secondary {
            border-color: rgba(55,65,81,0.9);
        }
        .cta-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 6px;
            font-size: 12px;
            color: #cbd5f5;
        }
        .cta-row small {
            color: #e5e7eb;
        }
        .cta-row a {
            color: #bfdbfe;
            text-decoration: none;
            font-weight: 500;
        }
        .footer {
            margin-top: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            color: #6b7280;
        }
        .dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #22c55e;
            box-shadow: 0 0 0 6px rgba(34,197,94,0.15);
            margin-right: 6px;
        }
    </style>
</head>
<body>
    <div class="shell">
        <div class="card">
            <div>
                <div class="title">Smart MCQ Quiz Platform</div>
                <div class="subtitle">
                    Questioners design rich, timed MCQs with flexible scoring rules.
                    Users take distraction‑free quizzes in fullscreen with precise control over skips, back navigation, and timers.
                </div>
                <div class="pill-row">
                    <div class="pill">Dual login: Questioner &amp; User</div>
                    <div class="pill">Per‑question timer &amp; skip rules</div>
                    <div class="pill">Range‑based scoring</div>
                    <div class="pill">Fullscreen exam mode</div>
                </div>
                <div class="feature-grid">
                    <div class="feature">
                        <div class="feature-title">Powerful MCQ builder</div>
                        <div class="feature-body">
                            Define options, correct answers, custom points, and advanced rules like
                            “0–100 ➜ 20 points, 100–500 ➜ 30 points”.
                        </div>
                    </div>
                    <div class="feature">
                        <div class="feature-title">Fine‑grained controls</div>
                        <div class="feature-body">
                            Configure per‑question timer, allow/deny skipping, show/hide skip button, and allow or block going back.
                        </div>
                    </div>
                    <div class="feature">
                        <div class="feature-title">Randomized exams</div>
                        <div class="feature-body">
                            Users get 30 random questions per quiz, with automatic scoring and a clean summary at the end.
                        </div>
                    </div>
                    <div class="feature">
                        <div class="feature-title">Secure fullscreen mode</div>
                        <div class="feature-body">
                            Quizzes run in fullscreen; leaving fullscreen or refreshing can be detected to discourage cheating.
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="auth-card">
                    <div class="auth-head">
                        <div class="auth-title">Get started</div>
                        <div class="badge">Laravel Quiz Portal</div>
                    </div>
                    <div class="auth-switch">
                        <a href="{{ route('register') }}" class="primary">
                            Create new account
                        </a>
                        <a href="{{ route('login') }}" class="secondary">
                            I already have an account
                        </a>
                    </div>
                    <div class="cta-row">
                        <div>
                            <small>Questioner?</small> Design and manage quizzes.
                        </div>
                        <div>
                            <small>User?</small> Join and attempt quizzes.
                        </div>
                    </div>
                </div>
                <div class="footer">
                    <div style="display:flex;align-items:center;">
                        <div class="dot"></div>
                        <span>Role‑aware dashboards for both Questioners and Users.</span>
                    </div>
                    <div>
                        Built with Laravel &amp; Blade
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>


