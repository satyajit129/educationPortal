<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashbaordController extends Controller
{
    public function studentDashboard()
    {
        $attempts = Auth::user()->attempts()->with('exam')->get();

        return view('student.pages.dashboard', compact('attempts'));
    }
}
