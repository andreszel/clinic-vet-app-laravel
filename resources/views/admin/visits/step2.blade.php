@extends('layouts.admin')

@section('content')
<!-- Page Heading -->
<div class="row">
    <div class="cold-md-12 mb-3">
        <h1 class="h3 mb-5 text-gray-800 d-inline">Nowa wizyta dla {{ $customer->name }} {{ $customer->surname }} - dodawanie leków</h1>
        @include('helpers.sections.info_form_add_edit_visit')
    </div>
</div>

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
                                <th>Formularz</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($medicals ?? [] as $medical)
                            <tr>
                                <td>{{ $counter++ }}.</td>
                                <td>{{ $medical->name }}</td>
                                <td class="text-right">
                                    <form class="form-inline" action="{{ route('visits.add_medical', ['id' => $visit->id, 'medical_id'=>$medical->id]) }}" method="POST">
                                        <input type="hidden" name="visit_id" value="{{$visit->id}}">
                                        <input type="hidden" name="medical_id" value="{{$medical->id}}">
                                        @csrf
                                        @method('POST')
                                        @php
                                        $default_value = 1;
                                        $step = 1;
                                        if($medical->unit_measure->id == 2) {
                                        $default_value = 100;
                                        $step = 5;
                                        }
                                        @endphp
                                        @if($medical->unit_measure->can_change_price)
                                        <label for="gross_price" class="mr-2">Cena brutto:</label>
                                        <input type="number" name="gross_price_sell" id="gross_price_sell" value="{{ $medical->gross_price_sell }}" class="form-control col-md-2 text-right mr-2" required="required" />
                                        @endif
                                        <label for="quantity">Ilość:</label>
                                        <input type="number" name="quantity" id="quantity" value="{{ $default_value }}" class="form-control col-md-2 mr-2" step="{{$step}}" placeholder="Wpisz ilość" required="required" />
                                        <span class="mr-2">{{ $medical->unit_measure->short_name }}</span>
                                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Dodaj do wizyty</button>
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
                        <tbody>
                            @foreach($visit_medicals ?? [] as $visit_medical)
                            <tr>
                                <td>{{ $counter_visit_medicals++ }}.</td>
                                <td>{{ $visit_medical->medical->name }}</td>
                                <td class="text-right">{{ $visit_medical->net_price }}</td>
                                <td class="text-right">{{ $visit_medical->gross_price }}</td>
                                <td class="text-center">{{ $visit_medical->vat->name }}</td>
                                <td class="text-center">{{ $visit_medical->quantity }} {{ $visit_medical->medical->unit_measure->short_name }}</td>
                                <td class="text-right">{{ Str::currency($visit_medical->sum_gross_price) }}</td>
                                <td>
                                    <form action="{{ route('visits.remove_medical', ['id' => $visit_medical->id, 'visit_id' => $visit->id]) }}" method="post">
                                        @method('DELETE')
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-sm text-danger mr-2" onclick="return confirm('Czy na pewno chcesz usunąć?')"><i class="fas fa-trash-alt"></i></button>
                                    </form>
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