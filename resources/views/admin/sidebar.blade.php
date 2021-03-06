<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
        <img class="img-fluid rounded mx-auto" src="{{ asset('img/trzaskacz-vet-weterynarz-piotrkow-trybunalski-logo.png') }}" alt="Logo" />
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ Request::routeIs('homeadmin') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('homeadmin') }}">
            <i class="fas fa-home"></i>
            <span>Pulpit</span></a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item {{ request()->routeIs('medicals.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('medicals.list') }}">
            <i class="fas fa-fw fa-file-medical"></i>
            <span>Leki weterynaryjne</span></a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item {{ request()->routeIs('additional-services.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('additionalservices.list') }}">
            <i class="fas fa-fw fa-puzzle-piece"></i>
            <span>Usługi dodatkowe</span></a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item {{ request()->routeIs('visits.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('visits.list') }}">
            <i class="fas fa-fw fa-briefcase-medical"></i>
            <span>Wizyty lekarskie</span></a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('customers.list') }}">
            <i class="fas fa-fw fa-user"></i>
            <span>Klienci kliniki</span></a>
    </li>

    @can('admin-level')
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Tables -->
    <li class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('users.list') }}">
            <i class="fas fa-fw fa-user-nurse"></i>
            <span>Użytkownicy</span></a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('reports.list') }}">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Raporty</span></a>
    </li>
    @endcan

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <li class="nav-item d-sm-none d-md-block">
        <div class="col-lg-12 mb-2">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    Stan konta
                    <div class="text-white-50 small">
                        <span class="d-block d-md-inline">2450.00</span>
                        <span class="d-block d-md-inline">PLN</span>
                    </div>
                </div>
            </div>
        </div>
    </li>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->