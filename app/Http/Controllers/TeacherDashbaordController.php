<?php

namespace App\Http\Controllers;


use App\Services\Teacher\DashboardService;
use Illuminate\Http\Request;

class TeacherDashbaordController extends Controller
{
    protected $dashboardService;
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function teacherDashboard()
    {
        return $this->dashboardService->renderDashboard();
    }
}
