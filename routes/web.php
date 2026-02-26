<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('landing');

// Temporary debug route to inspect current authentication/role in the browser
Route::get('/debug/whoami', function () {
    $user = auth()->user();
    return response()->json([
        'authenticated' => auth()->check(),
        'user' => $user ? [
            'id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
        ] : null,
    ]);
});

// Authentication
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register.post');

// Dashboards
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/questioner', [App\Http\Controllers\QuestionerDashboardController::class, 'index'])
        ->name('dashboard.questioner');

    Route::get('/dashboard/user', [App\Http\Controllers\UserDashboardController::class, 'index'])
        ->name('dashboard.user');

    // User quiz taking
    Route::middleware(['role:user'])->group(function () {
        Route::get('/quizzes/available', [App\Http\Controllers\UserQuizController::class, 'index'])
            ->name('user.quizzes.index');
        Route::post('/quizzes/{quiz}/start', [App\Http\Controllers\UserQuizController::class, 'start'])
            ->name('user.quizzes.start');
        Route::get('/attempts/{attempt}/question/{index}', [App\Http\Controllers\UserQuizController::class, 'showQuestion'])
            ->name('user.attempts.question');
        Route::post('/attempts/{attempt}/question/{index}', [App\Http\Controllers\UserQuizController::class, 'submitAnswer'])
            ->name('user.attempts.question.submit');
        // exit attempt early
        Route::post('/attempts/{attempt}/exit', [App\Http\Controllers\UserQuizController::class, 'exitAttempt'])
            ->name('user.attempts.exit');
        Route::get('/attempts/{attempt}/summary', [App\Http\Controllers\UserQuizController::class, 'summary'])
            ->name('user.attempts.summary');
    });

    // Questioner quiz management
    Route::middleware(['role:questioner'])->group(function () {
        Route::resource('quizzes', App\Http\Controllers\QuizController::class);
        Route::get('quizzes/{quiz}/questions/create', [App\Http\Controllers\QuestionController::class, 'create'])
            ->name('quizzes.questions.create');
        Route::post('quizzes/{quiz}/questions', [App\Http\Controllers\QuestionController::class, 'store'])
            ->name('quizzes.questions.store');
        Route::get('quizzes/{quiz}/questions/{question}/edit', [App\Http\Controllers\QuestionController::class, 'edit'])
            ->name('quizzes.questions.edit');
        Route::put('quizzes/{quiz}/questions/{question}', [App\Http\Controllers\QuestionController::class, 'update'])
            ->name('quizzes.questions.update');
    });
});
