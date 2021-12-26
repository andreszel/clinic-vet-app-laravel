@extends('layouts.admin')

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Klienci kliniki</h1>

@if(!$customers->isEmpty())
<div class="row mb-3">
    <div class="col-md-12">
        <a class="btn  btn-sm btn-primary float-right" href="{{ route('customers.create') }}" title="Dodaj nowego klienta"><i class="fas fa-fw fa-plus"></i> Dodaj</a>
    </div>
</div>
@endif

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Lista</h6>
    </div>
    <div class="card-body">

        @if(!$customers->isEmpty())
        <div class="col-xl-12 form-group" id="validation-message-customer"></div>

        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Lp.</th>
                        <th>Imię i nazwisko</th>
                        <th>Adres</th>
                        <th>Telefon</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Lp.</th>
                        <th>Imię i nazwisko</th>
                        <th>Adres</th>
                        <th>Telefon</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($customers ?? [] as $customer)
                    <tr>
                        <td>{{ $counter++ }}.</td>
                        <td>{{ $customer->name }} {{ $customer->surname }}</td>
                        <td>{{ $customer->address }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>
                            <form action="{{ route('customers.remove', ['id' => $customer->id]) }}" method="post">
                                @method('DELETE')
                                {{ csrf_field() }}
                                <a href="{{ route('customers.edit', ['id' => $customer->id]) }}" class="btn btn-sm text-primary mr-2" title="Edytuj klienta"><i class="fas fa-edit"></i></a>
                                <button type="submit" class="btn btn-sm text-danger mr-2" onclick="return confirm('Czy na pewno chcesz usunąć?')"><i class="fas fa-trash-alt"></i></button>
                            </form>
                            <form action="{{ route('visits.store_new_visit', ['customerId' => $customer->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm text-primary mr-2" title="Dodaj nową wizytę lekarską"><i class="fas fa-fw fa-briefcase-medical"></i></button>
                            </form>
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
            <p>Dodaj chociaż jednego klienta :)</p>
            <div class="col-md-12 text-center">
                <a class="btn btn-primary" href="{{ route('customers.create') }}" title="Dodaj nowego klienta"><i class="fas fa-fw fa-plus"></i> Dodaj</a>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection

@include('helpers.sections.datatables')