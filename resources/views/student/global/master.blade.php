

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Pass $settings to included CSS --}}
    @include('student.global.css_support')
    @yield('student_custom_style')
</head>

<body class="navbar-fixed sidebar-fixed" id="body">
    <div class="wrapper">
        @if (!Route::is('studentLogin'))
             @include('student.layouts.sidebar')
        @endif
       
        <div class="page-wrapper">
            {{-- Header --}}
            @if (!Route::is('studentLogin'))
                @include('student.layouts.header')
            @endif
            <div class="content-wrapper">
                <div class="content">
                    @yield('student_content')
                </div>
            </div>

            {{-- Footer --}}
            @include('student.layouts.footer')
        </div>
    </div>

    {{-- JS --}}
    @include('student.global.js_support')
    @yield('student_custom_js')
</body>

</html>

