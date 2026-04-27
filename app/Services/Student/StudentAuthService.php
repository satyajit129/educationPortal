<?php

namespace App\Services\Student;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentAuthService
{
    public function renderStudentLogin()
    {
        return view('student.pages.login');
    }

    public function handleStudentLoginRequest($request)
    {
        try {
            $request->validate([
                'email_or_mobile' => ['required'],
                'password' => ['required'],
            ]);

            $emailOrMobile = $request->email_or_mobile;
            $password = $request->password;

            $student = User::where(function ($query) use ($emailOrMobile) {
                $query->where('email', $emailOrMobile)
                    ->orWhere('mobile', $emailOrMobile);
            })
                ->where('is_student', 1)
                ->first();

            if ($student && Hash::check($password, $student->password)) {
                Auth::login($student);
                return redirect()->route('studentDashboard');
            }

            return redirect()->back()
                ->withErrors(['email_or_mobile' => 'Invalid credentials'])
                ->with('error', 'Invalid credentials')
                ->withInput();
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors(['error' => 'Something went wrong. Please try again.'])
                ->with('error', $th->getMessage())
                ->withInput();
        }
    }

    public function handleStudentLogout()
    {
        Auth::logout();
        return redirect()->route('studentLogin')->with('success', 'Logged out successfully');
    }
}
