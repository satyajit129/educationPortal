<header class="main-header" id="header">
    <nav class="navbar navbar-expand-lg navbar-light" id="navbar">
        <!-- Sidebar toggle button -->
        <button id="sidebar-toggler" class="sidebar-toggle">
            <span class="sr-only">Toggle navigation</span>
        </button>

        <span class="page-title">@yield('heading')</span>

        <div class="navbar-right ">


            <ul class="nav navbar-nav">
                <!-- Offcanvas -->

                <!-- User Account -->
                <li class="dropdown user-menu">
                    <button class="dropdown-toggle nav-link d-flex align-items-center" data-toggle="dropdown" style="gap: 8px;">
                        <!-- User Picture -->
                        
                        <!-- User Name -->
                        <span class="d-none d-lg-inline-block">{{ Auth::user()->name }}</span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-right">
                        <li>
                            <a class="dropdown-link-item" href="">
                                <i class="mdi mdi-account-outline"></i>
                                <span class="nav-text">My Profile</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-link-item" href="javascript:void(0)" data-toggle="modal" data-target="#logoutModal">
                                <i class="mdi mdi-logout"></i>
                                <span class="nav-text">Log Out</span>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>
</header>
<!-- Default Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to log out?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-pill" data-dismiss="modal">Cancel</button>
        
        <a href="" class="btn btn-danger btn-pill">Yes Logout!</a>
      </div>
    </div>
  </div>
</div>
