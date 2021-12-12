<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">WET APP</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="index.html">
            <i class="fas fa-home"></i>
            <span>Pulpit</span></a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="lista_lekow.html">
            <i class="fas fa-fw fa-file-medical"></i>
            <span>Leki weterynaryjne</span></a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="lista_uslug_dodatkowych.html">
            <i class="fas fa-fw fa-puzzle-piece"></i>
            <span>Usługi dodatkowe</span></a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="lista_wizyt.html">
            <i class="fas fa-fw fa-briefcase-medical"></i>
            <span>Wizyty lekarskie</span></a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="lista_klientow.html">
            <i class="fas fa-fw fa-user"></i>
            <span>Klienci kliniki</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Administrator
    </div>

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('doctors.list') }}">
            <i class="fas fa-fw fa-user-nurse"></i>
            <span>Lekarze</span></a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="raporty.html">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Raporty</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <li class="nav-item">
        <div class="col-lg-12 mb-4">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    Stan konta
                    <div class="text-white-50 small">2450.00 PLN</div>
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