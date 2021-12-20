@extends('layouts.admin')

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Usługi dodatkowe</h1>

@if(!$additionalservices->isEmpty())
<div class="row mb-3">
    <div class="col-md-12">
        <a class="btn  btn-sm btn-primary float-right" href="{{ route('additionalservices.create') }}" title="Dodaj nowy lek"><i class="fas fa-fw fa-plus"></i> Dodaj</a>
    </div>
</div>
@endif

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Lista</h6>
    </div>
    <div class="card-body">

        @if(!$additionalservices->isEmpty())
        <div class="col-xl-12 form-group" id="validation-message-additionalservice"></div>

        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Lp.</th>
                        <th>Nazwa</th>
                        <th>Cena netto [PLN]</th>
                        <th>Cena brutto [PLN]</th>
                        <th>VAT [PLN]</th>
                        <th class="text-center px-3">VAT [%]</th>
                        <th>Włączona</th>
                        <th>Dojazd</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Lp.</th>
                        <th>Nazwa</th>
                        <th>Cena netto [PLN]</th>
                        <th>Cena brutto [PLN]</th>
                        <th>VAT [PLN]</th>
                        <th class="text-center px-3">VAT [%]</th>
                        <th>Włączona</th>
                        <th>Dojazd</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($additionalservices ?? [] as $additionalservice)
                    <tr>
                        <td>{{ $counter++ }}.</td>
                        <td>{{ $additionalservice->name }}</td>
                        <td class="text-right">{{ $additionalservice->net_price }}</td>
                        <td class="text-right">{{ $additionalservice->gross_price }}</td>
                        <td class="text-right">{{ $additionalservice->gross_price-$additionalservice->net_price }}</td>
                        <td class="text-center px-3">{{ $additionalservice->vat->name }}</td>
                        <td>
                            {{ $additionalservice->active ? 'tak' : 'nie' }}

                            <a href="#" class="change-status {{ $additionalservice->active ? 'text-success' : 'text-secondary' }} float-right" title="{{ $additionalservice->active ? 'Wyłącz sprzedaż usługi' : 'Włącz sprzedaż usługi' }}" onclick="event.preventDefault(); document.getElementById('change-status-form-{{$additionalservice->id}}').submit();">
                                <i class="fas fa-check"></i>
                            </a>
                            <form id="change-status-form-{{$additionalservice->id}}" action="{{ route('additionalservices.change_status', ['id'=>$additionalservice->id]) }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </td>
                        <td>
                            {{ $additionalservice->drive_to_customer ? 'tak' : 'nie' }}

                            <a href="#" class="change-status {{ $additionalservice->drive_to_customer ? 'text-success' : 'text-secondary' }} float-right" title="{{ $additionalservice->drive_to_customer ? 'Usługa jest dojazdem' : 'Usługa nie jest dojazdem' }}" onclick="event.preventDefault(); document.getElementById('change-status-drive-to-customer-form-{{$additionalservice->id}}').submit();">
                                <i class="fas fa-check"></i>
                            </a>
                            <form id="change-status-drive-to-customer-form-{{$additionalservice->id}}" action="{{ route('additionalservices.change_status_drive_to_customer', ['id'=>$additionalservice->id]) }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('additionalservices.remove', ['id' => $additionalservice->id]) }}" method="post">
                                @method('DELETE')
                                {{ csrf_field() }}
                                <a href="{{ route('additionalservices.edit', ['id' => $additionalservice->id]) }}" class="btn btn-sm text-primary mr-2"><i class="fas fa-edit"></i></a>
                                <button type="submit" class="btn btn-sm text-danger mr-2" onclick="return confirm('Czy na pewno chcesz usunąć?')"><i class="fas fa-trash-alt"></i></button>
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
            <p>Dodaj chociaż jedną pozycję, żeby była sprzedaż. :)</p>
            <div class="col-md-12 text-center">
                <a class="btn btn-primary" href="{{ route('additionalservices.create') }}" title="Dodaj nowy lek"><i class="fas fa-fw fa-plus"></i> Dodaj</a>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection

@include('helpers.sections.datatables')