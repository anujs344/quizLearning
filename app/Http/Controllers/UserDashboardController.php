<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\QuizAttempt;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // gather some quick stats and recent attempts for the user dashboard
        $attempts = QuizAttempt::where('user_id', $user->id)
            ->with('quiz')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        $totalAttempts = QuizAttempt::where('user_id', $user->id)->count();
        $bestScore = QuizAttempt::where('user_id', $user->id)->max('total_score');

        return view('dashboards.user', compact('user', 'attempts', 'totalAttempts', 'bestScore'));
    }
}


