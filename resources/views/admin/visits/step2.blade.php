@extends('layouts.admin')

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Nowa wizyta dla {{ $customer->name }} {{ $customer->surname }} - dodawanie leków</h1>
@include('helpers.sections.info_form_add_edit_visit')

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><span class="text-uppercase">krok</span> {{ $currentStep }}/{{ $maxStep }}</h6>
    </div>
    <div class="card-body">
        <h5 class="mt-3 mb-4">Wyszukaj lek weterynaryjny</h5>
        <form id="visit-form-medicals" class="form-inline" action="{{ route('visits.step2', ['id'=>$visit->id]) }}" method="GET">
            @csrf
            <div class="row g-3 align-items-center">
                <div class="col-auto">
                    <label for="phrase">Nazwa leku</label>
                </div>
                <div class="col-auto">
                    <input value="{{ $phrase }}" id="phrase" name="phrase" type="text" class="form-control" autocomplete="off">
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Szukaj</button>
                </div>
            </div>
        </form>

        <div class="row mb-5">
            <div class="col-md-12">
                @if($errors->any())
                {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @if(count($medicals) > 0)
                <h6 class="mb-3 font-weight-bold text-primary">Lista znalezionych leków dla frazy <span class="text-warning">"{{ $phrase }}"</span></h6>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Lp.</th>
                                <th>Nazwa leku</th>
                                <th class="text-right">Cena netto [PLN]</th>
                                <th class="text-right">Cena brutto [PLN]</th>
                                <th class="text-center">VAT [%]</th>
                                <th class="text-right">Suma</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Lp.</th>
                                <th>Nazwa leku</th>
                                <th class="text-right">Cena netto [PLN]</th>
                                <th class="text-right">Cena brutto [PLN]</th>
                                <th class="text-center">VAT [%]</th>
                                <th class="text-right">Suma</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($medicals ?? [] as $medical)
                            <tr>
                                <td>{{ $counter++ }}.</td>
                                <td>{{ $medical->name }}</td>
                                <td class="text-right">{{ $medical->net_price_sell }}</td>
                                <td class="text-right">{{ $medical->gross_price_sell }}</td>
                                <td class="text-center">{{ $medical->vat_sell->name }}</td>
                                <td class="text-right"></td>
                                <td>
                                    <form class="form-inline" action="{{ route('visits.add_medical', ['id' => $visit->id, 'medical_id'=>$medical->id]) }}" method="POST">
                                        <input type="hidden" name="visit_id" value="{{$visit->id}}">
                                        <input type="hidden" name="medical_id" value="{{$medical->id}}">
                                        @csrf
                                        @method('POST')
                                        <div class="col-auto">
                                            @php
                                            $default_value = 1;
                                            if($medical->unit_measure->id == 2) $default_value = 100;
                                            @endphp
                                            <input type="number" name="quantity" id="quantity" value="{{ $default_value }}" class="form-control" placeholder="Wpisz ilość" required="required" />
                                        </div>
                                        <div class="col-auto">{{ $medical->unit_measure->name }}</div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-plus-circle"></i></button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <hr />
                <div class="text-center my-3">
                    @if($phrase)
                    <h4 class="text-info">UPSSS ... Nie znaleziono żadnych leków zawierających podaną frazę. Wpisz coś innego.</h4>
                    @else
                    <h4 class="text-info">Wpisz szukaną frazę.</h4>
                    @endif
                    <h1><i class="fas fa-ban"></i></h1>
                </div>
                @endif
            </div>
        </div>

        <hr />
        <div class="row">
            <div class="col-md-12 mt-3">
                <h6 class="mb-3 font-weight-bold text-primary">Lista leków dodanych do wizyty</h6>
                @if(count($visit_medicals) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Lp.</th>
                                <th>Nazwa leku</th>
                                <th class="text-right">Cena netto [PLN]</th>
                                <th class="text-right">Cena brutto [PLN]</th>
                                <th class="text-center">VAT [%]</th>
                                <th class="text-center">Ilość</th>
                                <th class="text-right">Suma [PLN]</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Lp.</th>
                                <th>Nazwa leku</th>
                                <th class="text-right">Cena netto [PLN]</th>
                                <th class="text-right">Cena brutto [PLN]</th>
                                <th class="text-center">VAT [%]</th>
                                <th class="text-center">Ilość</th>
                                <th class="text-right">Suma [PLN]</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($visit_medicals ?? [] as $visit_medical)
                            @php $suma = number_format($visit_medical->quantity*$visit_medical->gross_price,2); $sum_all_medicals += $suma; @endphp
                            <tr>
                                <td>{{ $counter_visit_medicals++ }}.</td>
                                <td>{{ $visit_medical->medical->name }}</td>
                                <td class="text-right">{{ $visit_medical->net_price }}</td>
                                <td class="text-right">{{ $visit_medical->gross_price }}</td>
                                <td class="text-center">{{ $visit_medical->vat->name }}</td>
                                <td class="text-center">{{ $visit_medical->quantity }} {{ $visit_medical->medical->unit_measure->short_name }}</td>
                                <td class="text-right">{{ $suma }}</td>
                                <td>

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row my-5">
                    <div class="col-md-12 text-right">
                        <h4>Suma wszystkich leków: {{ Str::currency($sum_all_medicals) }} PLN</h4>
                    </div>
                </div>
                @else
                <hr />
                <div class="text-center my-3">
                    @if($phrase)
                    <h4 class="text-info">UPSSS ... Nie znaleziono żadnych leków dodanych do wizyty.</h4>
                    @endif
                    <h1><i class="fas fa-ban"></i></h1>
                </div>
                @endif

            </div>
        </div>

        <div class="row mt-4 mb-1">
            <div class="col-md-12">
                <a href="{{ route('visits.step1', ['id'=>$visit->id]) }}" class="btn btn-success float-left" title="Krok 1 - parametry podstawowe">Krok 1 - parametry podstawowe</a>
                <a href="{{ route('visits.step3', ['id'=>$visit->id]) }}" class="btn btn-success float-right" title="Krok 3 - dodawanie usług">Krok 3 - usługi dodatkowe</a>
            </div>
        </div>

    </div>
</div>
@endsection