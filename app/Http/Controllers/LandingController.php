<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    public function index()
    {
        // If a user is already authenticated, redirect them to their dashboard
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'questioner') {
                return redirect()->route('dashboard.questioner');
            }

            return redirect()->route('dashboard.user');
        }

        return view('landing');
    }
}


