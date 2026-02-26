# Motive — Quiz Platform

This repository contains the Motive quiz application — a Laravel-based system for creating quizzes and delivering them to users with flexible scoring rules.

Key features

- Create quizzes and multiple-choice questions
- Per-option integer `points` (default 0) for partial credit or penalties
- Optional range-based scoring (4 ranges when used) with non-overlapping validation
- User quiz attempts with per-question awarded points and summaries
- Role-based UI for `questioner` (authors) and `user` (takers)

Quickstart (development)

Requirements: PHP 7.4+, Composer, a local webserver and a database (MySQL, SQLite, etc.)

1. Install dependencies

```powershell
cd d:\xampp7.4\htdocs\motive\motive
composer install
```

2. Copy and configure environment

```powershell
copy .env.example .env
# edit .env to set DB_* values
php artisan key:generate
```

3. Run migrations

```powershell
php artisan migrate
```

4. Create users (questioner and normal user) via tinker or seeders

```powershell
php artisan tinker
# Example:
App\Models\User::create(['name'=>'Admin','email'=>'admin@example.test','password'=>Hash::make('secret'),'role'=>'questioner']);
```

5. Serve locally

```powershell
php artisan serve --port=8000
```

Open http://127.0.0.1:8000 in your browser.

Running tests

Run PHPUnit from the project root:

```powershell
vendor\bin\phpunit
```

Important notes

- The question create/edit views intentionally do not pre-fill scoring range values. Empty min/max are ignored by the server so accidental default ranges are not created.
- Server-side validation only persists `ScoringRule` rows when both `min_value` and `max_value` are explicitly numeric.
- If you want per-question countdowns, add or use the `time_limit_seconds` column on questions and set it when creating questions.

Maintenance & troubleshooting

- Clear compiled views after making template changes:

```powershell
php artisan view:clear
php artisan cache:clear
```

- If you need a cleanup script to remove accidental scoring rules created before the validation fix, I can add a small artisan command to help.

Contact & next steps

If you'd like, I can:

- Add a UI control so questioners can set `time_limit_seconds` per quiz
- Add client-side logic to hide empty scoring rows before submit
- Add a cleanup script to detect and remove suspicious scoring rules

Pick one and I'll implement it.

---
