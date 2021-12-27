@extends('layouts.admin')

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Raporty</h1>

<div class="row mb-3">
    <div class="col-md-12">
        <a class="btn  btn-sm btn-primary float-right" href="{{ route('reports.list') }}" title="Dodaj nowy lek"><i class="fas fa-fw fa-plus"></i> Dodaj</a>
    </div>
</div>


@endsection

@include('helpers.sections.datatables')