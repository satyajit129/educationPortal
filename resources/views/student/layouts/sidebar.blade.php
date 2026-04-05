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
                <li class="{{ Route::is('studentDashboard') ? 'active' : '' }}">
                    <a href="{{ route('studentDashboard') }}" class="sidenav-item-link">
                        <i class="mdi mdi-home-outline"></i>
                        <span class="nav-text">ড্যাশবোর্ড</span>
                    </a>
                </li>

            </ul>
        </div>


    </div>
</aside>
