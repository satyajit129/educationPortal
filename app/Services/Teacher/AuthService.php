<?php


namespace App\Services\Teacher;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class AuthService
{
    public function renderLogin()
    {
        return view('teacher.pages.login');
    }
    public function handleLoginRequest($request)
    {
        try {
            $request->validate(
                [
                    'email'    => 'required|email',
                    'password' => 'required',
                ],
                [
                    'email.required'    => 'ইমেইল ঠিকানা অবশ্যই দিতে হবে।',
                    'email.email'       => 'দয়া করে একটি বৈধ ইমেইল ঠিকানা লিখুন।',
                    'password.required' => 'পাসওয়ার্ড অবশ্যই দিতে হবে।',
                ]
            );

            $credentials = $request->only('email', 'password');

            // 🔐 Step 1: Attempt login
            if (!Auth::attempt($credentials)) {
                return back()
                    ->with('error', 'ইমেইল অথবা পাসওয়ার্ড সঠিক নয়।')
                    ->withInput();
            }

            $user = Auth::user();

            // 🧠 Step 2: Check teacher role
            $isTeacher = $user->accessRoles()
                ->where('slug', 'teacher')
                ->exists();

            if (!$isTeacher) {
                Auth::logout();

                return back()
                    ->with('error', 'আপনার Teacher হিসেবে লগইন করার অনুমতি নেই।')
                    ->withInput();
            }

            // ✅ Step 3: Login successful
            return redirect()->route('teacherDashboard')
                ->with('success', 'সফলভাবে Teacher হিসেবে লগইন হয়েছে।');
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->getMessage())
                ->with('error', $e->getMessage())
                ->withInput();
        } catch (Throwable $th) {
            Log::error('Error: ' . $th->getMessage());
            Auth::logout();

            return back()
                ->with('error', 'লগইন ব্যর্থ হয়েছে!')
                ->withInput();
        }
    }
    public function logout(): void
    {
        Auth::guard('teacher')->logout();
    }
}
