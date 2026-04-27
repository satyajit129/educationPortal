@extends('student.global.master')


@section('student_custom_style')
    @if (Route::is('studentLogin'))
        <style>
            @media (min-width: 768px) {

                .sidebar-fixed-offcanvas .main-header,
                .sidebar-fixed .main-header {
                    padding-left: 0;
                }
            }
            @media (min-width: 768px) {

                .sidebar-fixed-offcanvas .page-wrapper,
                .sidebar-fixed .page-wrapper {
                    padding-left: 0;
                }
            }
            .content {
                height: 100%;
                display: flex;
                justify-content: center;
            }
        </style>
    @endif
@endsection


@section('student_content')
    <div class="container d-flex justify-content-center align-items-center">
        <div class="card shadow-lg" style="max-width: 500px; width: 100%;">
            <div class="card-body">
                <h4 class="text-center mb-4 border-bottom-1">Benzir's Job Aid (Student Login)</h4>

                <form action="{{ route('studentLoginRequest') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email_or_mobile" class="form-label">ইমেইল/মোবাইল নং</label>
                        <input type="email_or_mobile" class="form-control" id="email_or_mobile" name="email_or_mobile" placeholder="ইমেইল/মোবাইল নং"
                            required value="{{ old('email_or_mobile') }}">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">পাসওয়ার্ড</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="পাসওয়ার্ড লিখুন" required>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary w-100">লগইন</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('student_custom_js')
    
@endsection
