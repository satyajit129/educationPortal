

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Pass $settings to included CSS --}}
    @include('teacher.global.css_support')
    @yield('teacher_custom_style')
</head>

<body class="navbar-fixed sidebar-fixed" id="body">
    <div class="wrapper">
        @if (!Route::is('teacherLogin', 'teacherRegister'))
             @include('teacher.layouts.sidebar')
        @endif
       
        <div class="page-wrapper">
            {{-- Header --}}
            @if (!Route::is('teacherLogin', 'teacherRegister'))
                @include('teacher.layouts.header')
            @endif
            <div class="content-wrapper">
                <div class="content">
                    @yield('teacher_content')
                </div>
            </div>

            {{-- Footer --}}
            @include('teacher.layouts.footer')
        </div>
    </div>

    {{-- JS --}}
    @include('teacher.global.js_support')
    @yield('teacher_custom_js')
</body>

</html>

