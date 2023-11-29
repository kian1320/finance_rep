<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}"
                    href="{{ url('admin/dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>


                <a class="nav-link {{ Request::is('admin/users') ? 'active' : '' }}" href="{{ url('admin/users') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-user fa-fw"></i></div>
                    Users
                </a>

                <a class="nav-link {{ Request::is('admin/reports') ? 'active' : '' }}"
                    href="{{ url('admin/reports') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-user fa-fw"></i></div>
                    Submitted Montly Report
                </a>

                <a class="nav-link {{ Request::is('admin/appreports') ? 'active' : '' }}"
                    href="{{ url('admin/appreports') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-user fa-fw"></i></div>
                    Approved Montly Report
                </a>


            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:{{ strtoupper(Auth::user()->name) }}</div>

        </div>
    </nav>
</div>
