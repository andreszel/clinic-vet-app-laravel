<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Andrzej Szelka">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('appTitle', $appTitle)</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    @yield('datatables-css')
    @yield('datetime-visit-css')
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        @include('admin.sidebar')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">


            <!-- Main Content -->
            <div id="content">

                @include('admin.topbar')

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    @include('flash-message')

                    @yield('content')
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            @include('admin.footer')

        </div>

    </div><!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Czy na pewno chcesz się wylogować?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Kliknij poniżej Wyloguj się, jeżeli chcesz zakończyć sesję.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Anuluj</button>

                    <a class="btn btn-primary" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __('Wyloguj się') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('js/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('js/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('js/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    @yield('datatables-js')

    <script>
        $(document).ready(function() {
            $.noConflict();
        });
    </script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    @yield('charts')

    @yield('datetime-visit-js')

    <script type="text/javascript">
        @yield('javascript')
    </script>

    @yield('js-files')
</body>

</html>