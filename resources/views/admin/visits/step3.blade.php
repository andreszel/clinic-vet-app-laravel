@extends('layouts.admin')

@section('content')
<!-- Page Heading -->
<div class="row">
    <div class="cold-md-12 mb-3">
        <h1 class="h3 text-gray-800 d-inline">Nowa wizyta dla {{ $customer->name }} {{ $customer->surname }} - dodawanie usług dodatkowych</h1>
        @include('helpers.sections.info_form_add_edit_visit')
    </div>
</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><span class="text-uppercase">krok</span> {{ $currentStep }}/{{ $maxStep }}</h6>
    </div>
    <div class="card-body">
        <h5 class="mt-3 mb-4">Wyszukaj usługę dodatkową</h5>
        <form id="visit-form-services" class="form-inline" action="{{ route('visits.step3', ['id'=>$visit->id]) }}" method="GET">
            @csrf
            <div class="row g-3 align-items-center">
                <div class="col-auto">
                    <label for="phrase">Nazwa usługi</label>
                </div>
                <div class="col-auto">
                    <input value="{{ $phrase }}" id="phrase" name="phrase" type="text" class="form-control" autocomplete="off">
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Szukaj</button>
                </div>
            </div>
        </form>

        @if($errors->any())
        <div class="row mt-3 mb-5">
            <div class="col-md-12">
                {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                @if(count($additional_services) > 0)
                <h6 class="mt-3 mb-3 font-weight-bold text-primary">Lista znalezionych leków dla frazy <span class="text-warning">"{{ $phrase }}"</span></h6>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Lp.</th>
                                <th>Nazwa usługi</th>
                                <th class="text-right">Cena netto [PLN] @include('helpers.sections.nightly_visit_icon', ['nightly_visit' => $visit->nightly_visit])</th>
                                <th class="text-right">Cena brutto [PLN] @include('helpers.sections.nightly_visit_icon', ['nightly_visit' => $visit->nightly_visit])</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($additional_services ?? [] as $additional_service)
                            <tr>
                                <td>{{ $counter++ }}.</td>
                                <td>{{ $additional_service->name }}</td>
                                <td class="text-right">{{ ($visit->nightly_visit ? $additional_service->nightly_net_price : $additional_service->net_price) }}</td>
                                <td class="text-right">{{ ($visit->nightly_visit ? $additional_service->nightly_gross_price : $additional_service->gross_price) }}</td>
                                <td>
                                    <form class="form-inline" action="{{ route('visits.add_additional_service', ['id' => $visit->id, 'additional_service_id'=>$additional_service->id]) }}" method="POST">
                                        <input type="hidden" name="visit_id" value="{{$visit->id}}">
                                        <input type="hidden" name="additional_service_id" value="{{$additional_service->id}}">
                                        <input type="hidden" name="set_price_in_visit" value="{{$additional_service->set_price_in_visit}}">
                                        @csrf
                                        @method('POST')
                                        <input type="number" name="gross_price" id="gross_price" value="{{ $additional_service->set_price_in_visit ? 0 : ($visit->nightly_visit ? $additional_service->nightly_gross_price : $additional_service->gross_price) }}" min="{{ $additional_service->set_price_in_visit ? 0 : $additional_service->gross_price }}" class="form-control col-md-3 text-right mr-2" placeholder="Wpisz cenę" required="required" />
                                        <span class="mr-2">Ilość:</span>
                                        <input type="number" name="quantity" id="quantity" value="1" class="form-control col-md-2 mr-2" placeholder="Wpisz ilość" required="required" />
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
                <h6 class="mb-3 font-weight-bold text-primary">Lista usług dodatkowych dodanych do wizyty</h6>
                @if(count($visit_additional_services) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Lp.</th>
                                <th>Nazwa leku</th>
                                <th class="text-right">Cena brutto [PLN] @include('helpers.sections.nightly_visit_icon', ['nightly_visit' => $visit->nightly_visit])</th>
                                <th class="text-center">Ilość</th>
                                <th class="text-right">Suma [PLN]</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($visit_additional_services ?? [] as $visit_additional_service)
                            <tr>
                                <td>{{ $counter_visit_additional_services++ }}.</td>
                                <td>{{ $visit_additional_service->additionalservice->name }}</td>
                                <td class="text-right">{{ $visit_additional_service->gross_price }}</td>
                                <td class="text-center">{{ $visit_additional_service->quantity }}</td>
                                <td class="text-right">{{ $visit_additional_service->sum_gross_price }}</td>
                                <td>
                                    <form action="{{ route('visits.remove_additional_service', ['id' => $visit_additional_service->id, 'visit_id' => $visit->id]) }}" method="post">
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
                        <h4>Suma wszystkich dodanych usług: {{ Str::currency($sum_all_additional_services) }} PLN</h4>
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
                <a href="{{ route('visits.step2', ['id'=>$visit->id]) }}" class="btn btn-success float-left" title="Krok 2 - parametry podstawowe">Krok 2 - dodawanie leków</a>
                <a href="{{ route('visits.summary', ['id'=>$visit->id]) }}" class="btn btn-success float-right" title="Krok 4 - Podsumowanie">Krok 4 - podsumowanie</a>
            </div>
        </div>

    </div>
</div>
@endsection

@include('helpers.sections.format_price')