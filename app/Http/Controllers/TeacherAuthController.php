<?php

namespace App\Http\Controllers;

use App\Services\Teacher\AuthService;
use Illuminate\Http\Request;

class TeacherAuthController extends Controller
{
    protected $authService;


    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function teacherLogin()
    {
        return $this->authService->renderLogin();
    }

    public function teacherLoginRequest(Request $request)
    {
        return $this->authService->handleLoginRequest($request);
    }
}
