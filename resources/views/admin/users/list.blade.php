@extends('layouts.admin')

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Lekarze</h1>

@if(!$users->isEmpty())
<div class="row mb-3">
    <div class="col-md-12">
        <a class="btn  btn-sm btn-primary float-right" href="{{ route('users.create') }}" title="Dodaj nowego lekarza"><i class="fas fa-fw fa-plus"></i> Dodaj</a>
    </div>
</div>
@endif

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Lista</h6>
    </div>
    <div class="card-body">

        @if(!$users->isEmpty())
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Lp.</th>
                        <th>Imię i nazwisko</th>
                        <th>Email</th>
                        <th>Telefon</th>
                        <th>Prowizja usługi/leki</th>
                        <th>Konto aktywne</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Lp.</th>
                        <th>Imię i nazwisko</th>
                        <th>Email</th>
                        <th>Telefon</th>
                        <th>Prowizja usługi/leki</th>
                        <th>Konto aktywne</th>
                        <th>#</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($users ?? [] as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }} {{ $user->surname }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->commission_services }}/{{ $user->commission_medicals }}</td>
                        <td>{{ $user->active ? 'tak' : 'nie' }}</td>
                        <td>
                            <a href="profil_lekarza.html" class="text-info mr-2" title="Profil lekarza">
                                <i class="fas fa-user"></i>
                            </a>
                            <a href="nowy_lekarz.html" class="text-primary mr-2" title="Edytuj">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" class="text-danger mr-2" title="Usuń">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center my-3">
            <h4 class="text-info">Lista jest pusta</h4>
            <h1><i class="fas fa-ban"></i></h1>
            <p>Dodaj chociaż jednego, ktoś musi pracować. :)</p>
            <div class="col-md-12 text-center">
                <a class="btn btn-primary" href="{{ route('users.create') }}" title="Dodaj nowego lekarza"><i class="fas fa-fw fa-plus"></i> Dodaj</a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@include('helpers.sections.datatables')