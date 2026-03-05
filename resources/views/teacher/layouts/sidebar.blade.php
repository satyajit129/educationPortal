<aside class="left-sidebar bg-sidebar">
    <div id="sidebar" class="sidebar sidebar-with-footer">
        <div class="app-brand">
            <a href='#' title="Dashboard">
                {{-- <span class="brand-name text-truncate">{{ $settings->website_short_name }}</span> --}}
            </a>
        </div>
        <div class="sidebar-scrollbar">
            <ul class="nav sidebar-inner" id="sidebar-menu">
                <!-- Dashboard -->
                <li class="{{ Route::is('teacherDashboard') ? 'active' : '' }}">
                    <a href="{{ route('teacherDashboard') }}" class="sidenav-item-link">
                        <i class="mdi mdi-home-outline"></i>
                        <span class="nav-text">ড্যাশবোর্ড</span>
                    </a>
                </li>
                @php
                    $questionCategoryRoute = ['questionCategoryList', 'questionCategoryCreate', 'questionCategoryEdit'];
                @endphp
                {{-- Question Category --}}
                <li class="{{ Route::is($questionCategoryRoute) ? 'active' : '' }}">
                    <a href="{{ route('questionCategoryList') }}" class="sidenav-item-link">
                        <i class="mdi mdi-folder-multiple-outline"></i>
                        <span class="nav-text">প্রশ্নের ক্যাটাগরি</span>
                    </a>
                </li>
                {{-- Year List --}}
                @php
                    $yearRoutes = [
                        'yearList',
                        'yearForm',
                    ];
                @endphp
                <li class="{{ Route::is($yearRoutes) ? 'active' : '' }}">
                    <a href="{{ route('yearList') }}" class="sidenav-item-link">
                        <i class="mdi mdi-calendar-range"></i>
                        <span class="nav-text">বছর সমূহ</span>
                    </a>
                </li>
                {{-- Previous Exam Category --}}
                @php
                    $previousExamCategoryRoutes = [
                        'previousExamCategoryList',
                        'previousExamCategoryForm',
                        'previousExamListForm'
                    ];
                @endphp
                {{-- Previous Exam Category --}}
                <li class="{{ Route::is($previousExamCategoryRoutes) ? 'active' : '' }}">
                    <a href="{{ route('previousExamCategoryList') }}" class="sidenav-item-link">
                        <i class="mdi mdi-history"></i>
                        <span class="nav-text">পূর্ববর্তী পরীক্ষা</span>
                    </a>
                </li>
                @php
                    $questionRoute = ['questionList', 'questionForm'];
                @endphp

                {{-- Questions --}}
                <li class="{{ Route::is($questionRoute) ? 'active' : '' }}">
                    <a href="{{ route('questionList') }}" class="sidenav-item-link">
                        <i class="mdi mdi-help-circle-outline"></i>
                        <span class="nav-text">প্রশ্ন সমূহ</span>
                    </a>
                </li>

                {{-- Question Builder --}}
                <li class="{{ Route::is('selectExamQuestion') ? 'active' : '' }}">
                    <a href="{{ route('selectExamQuestion') }}" class="sidenav-item-link">
                        <i class="mdi mdi-tools"></i>
                        <span class="nav-text">প্রশ্ন বিল্ডার</span>
                    </a>
                </li>

            </ul>
        </div>


    </div>
</aside>
