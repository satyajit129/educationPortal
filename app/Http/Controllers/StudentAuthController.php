<?php

namespace App\Http\Controllers;

use App\Services\Student\StudentAuthService;
use Illuminate\Http\Request;

class StudentAuthController extends Controller
{
    protected StudentAuthService $studentAuthService;
    public function __construct(StudentAuthService $studentAuthService)
    {
        $this->studentAuthService = $studentAuthService;
    }

    public function studentLogin()
    {
        return $this->studentAuthService->renderStudentLogin();
    }

    public function studentLoginRequest(Request $request)
    {
        return $this->studentAuthService->handleStudentLoginRequest($request);
    }
    public function studentLogout()
    {
        return $this->studentAuthService->handleStudentLogout();
    }
}
