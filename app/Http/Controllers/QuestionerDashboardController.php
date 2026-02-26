<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class QuestionerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('dashboards.questioner', compact('user'));
    }
}


