@extends('layouts.admin')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Raporty</h1>
</div>

<div class="d-none d-lg-block">
    <p class="mb-4">Incididunt cupidatat elit labore sit. Consequat aliqua occaecat aute anim magna proident pariatur commodo est ea cupidatat exercitation fugiat minim. Esse exercitation in est nulla tempor ad cillum ullamco. Ullamco anim laboris proident esse consectetur non qui. Mollit ipsum cupidatat id est excepteur incididunt aute.
        <a target="_blank" href="https://datatables.net">Pomoc</a>.
    </p>
</div>

@include('admin.reports.search')

@include('admin.reports.report1')

@include('admin.reports.report2')

@include('admin.reports.report3')

@include('admin.reports.report4')

@include('admin.reports.report5')

@endsection

@include('helpers.sections.datatables')
@include('helpers.sections.datetime')