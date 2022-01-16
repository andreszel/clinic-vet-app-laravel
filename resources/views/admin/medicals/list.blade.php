@extends('layouts.admin')

@section('content')
<!-- Page Heading -->
<div class="mb-3">
    <h1 class="h3 mb-2 text-gray-800 d-inline">Leki weterynaryjne</h1>

    @if(!$medicals->isEmpty())
    @can('admin-level')
    <a class="btn btn-sm btn-primary float-right" href="{{ route('medicals.create') }}" title="Dodaj nowy lek"><i class="fas fa-fw fa-plus"></i> Dodaj</a>
    @endcan
    @endif

    @can('admin-level')
    <form action="{{ route('medicals.file_import') }}" class="float-right form-inline mr-3" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" class="form-control  form-control-sm" required="required">
        <button class="btn btn-sm btn-success">Importuj leki</button>
    </form>
    @endcan
</div>

@include('admin.medicals.search')

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Lista</h6>
    </div>
    <div class="card-body">

        @if(!$medicals->isEmpty())
        <div class="col-xl-12 form-group" id="validation-message-medical"></div>

        {{ $medicals->links() }}


        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">

                @can('admin-level')
                <thead>
                    <tr>
                        <th rowspan="2">Lp.</th>
                        <th rowspan="2">Nazwa leku</th>
                        <th colspan="3" class="text-center">Zakup</th>
                        <th colspan="3" class="text-center">Sprzedaż</th>
                        <th colspan="2" class="text-center">Marża</th>
                        <th rowspan="2">Action</th>
                    </tr>
                    <tr>
                        <th>Netto [pln]</th>
                        <th>Brutto [pln]</th>
                        <th>VAT</th>
                        <th>Netto [pln]</th>
                        <th>Brutto [pln]</th>
                        <th>VAT</th>
                        <th>Netto [pln]</th>
                        <th>Brutto [pln]</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th rowspan="2">Lp.</th>
                        <th rowspan="2">Nazwa leku</th>
                        <th>Netto [pln]</th>
                        <th>Brutto [pln]</th>
                        <th>VAT</th>
                        <th>Netto [pln]</th>
                        <th>Brutto [pln]</th>
                        <th>VAT</th>
                        <th>Netto [pln]</th>
                        <th>Brutto [pln]</th>
                        <th rowspan="2">Action</th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-center">Zakup</th>
                        <th colspan="3" class="text-center">Sprzedaż</th>
                        <th colspan="2" class="text-center">Marża</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($medicals ?? [] as $medical)
                    <tr>
                        <td>{{ $counter++ }}.</td>
                        <td>
                            {{ $medical->name }} ({{ $medical->unit_measure->name }})

                            <a href="#" class="change-status {{ $medical->active ? 'text-success' : 'text-secondary' }} float-right" title="{{ $medical->active ? 'Wyłącz sprzedaż leku' : 'Włącz sprzedaż leku' }}" onclick="event.preventDefault(); document.getElementById('change-status-form-{{$medical->id}}').submit();">
                                <i class="fas fa-check"></i>
                            </a>
                            <form id="change-status-form-{{$medical->id}}" action="{{ route('medicals.change_status', ['id'=>$medical->id]) }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </td>
                        <td class="text-right">{{ $medical->net_price_buy }}</td>
                        <td class="text-right">{{ $medical->gross_price_buy }}</td>
                        <td class="text-center px-3">{{ $medical->vat_buy->name }}%</td>
                        <td class="text-right">{{ $medical->net_price_sell }}</td>
                        <td class="text-right">{{ $medical->gross_price_sell }}</td>
                        <td class="text-center px-3">{{ $medical->vat_sell->name }}%</td>
                        <td class="text-right">{{ $medical->net_margin }}</td>
                        <td class="text-right">{{ $medical->gross_margin }}</td>
                        <td>
                            <form action="{{ route('medicals.remove', ['id' => $medical->id]) }}" method="post">
                                @method('DELETE')
                                {{ csrf_field() }}
                                <a href="{{ route('medicals.edit', ['id' => $medical->id]) }}" class="btn btn-sm text-primary mr-2"><i class="fas fa-edit"></i></a>
                                <button type="submit" class="btn btn-sm text-danger mr-2" onclick="return confirm('Czy na pewno chcesz usunąć?')"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                @endcan

                @cannot('admin-level')

                <thead>
                    <tr>
                        <th>Lp.</th>
                        <th>Nazwa leku</th>
                        <th>Wartość netto PLN</th>
                        <th class="text-center">Stawka VAT %</th>
                        <th>Kwota VAT PLN</th>
                        <th>Wartość brutto PLN</th>
                    </tr>
                </thead>
                <tfoot>
                    <th>Lp.</th>
                    <th>Nazwa leku</th>
                    <th>Wartość netto PLN</th>
                    <th class="text-center">Stawka VAT %</th>
                    <th>Kwota VAT PLN</th>
                    <th>Wartość brutto PLN</th>
                </tfoot>
                <tbody>
                    @foreach($medicals ?? [] as $medical)
                    <tr>
                        <td>{{ $counter++ }}.</td>
                        <td>{{ $medical->name }} ({{ $medical->unit_measure->name }})</td>
                        <td class="text-right">{{ $medical->net_price_sell }}</td>
                        <td class="text-center px-3">{{ $medical->vat_sell->name }}</td>
                        <td class="text-right">{{ number_format($medical->gross_price_sell-$medical->net_price_sell,2) }}</td>
                        <td class="text-right">{{ $medical->gross_price_sell }}</td>
                    </tr>
                    @endforeach
                </tbody>

                @endcannot

            </table>
        </div>

        {{ $medicals->links() }}
        @else
        <div class="text-center my-3">
            <h4 class="text-info">Lista jest pusta</h4>
            <h1><i class="fas fa-ban"></i></h1>
            @can('admin-level')
            <p>Dodaj chociaż jedną pozycję, żeby była sprzedaż. :)</p>
            <div class="col-md-12 text-center">
                <a class="btn btn-primary" href="{{ route('medicals.create') }}" title="Dodaj nowy lek"><i class="fas fa-fw fa-plus"></i> Dodaj</a>
            </div>
            @endcan
        </div>
        @endif
    </div>
</div>

@endsection

@include('helpers.sections.datatables')