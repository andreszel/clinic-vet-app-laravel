@extends('layouts.admin')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Raporty</h1>
</div>

<div class="d-none d-lg-block">
    <p class="mb-4">
        Wyszukiwarka ustawia domyślne parametry data od i data do, jeżeli nie wybierzemy żadnych parametrów.
    </p>
</div>

@include('admin.reports.search')

@include('admin.reports.report1')

@include('admin.reports.report6')

@include('admin.reports.report5')

@include('admin.reports.report3')

@include('admin.reports.report2')

@include('admin.reports.report4')

@endsection

@include('helpers.sections.datatables')
@include('helpers.sections.datetime')