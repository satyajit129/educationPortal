<?php 


namespace App\Services\Teacher;

class DashboardService
{
    public function renderDashboard()
    {
        return view('teacher.pages.dashboard');
    }
}