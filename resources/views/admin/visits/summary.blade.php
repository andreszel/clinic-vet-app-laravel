@extends('layouts.admin')

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Nowa wizyta dla {{ $customer->name }} {{ $customer->surname }} - podsumowanie</h1>
@include('helpers.sections.info_form_add_edit_visit')

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><span class="text-uppercase">krok</span> {{ $currentStep }}/{{ $maxStep }}</h6>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-md-12 mt-3 mb-4">
                <legend>Dane Klienta</legend>
                <div class="form-group row">
                    <label for="customer_name" class="col-4 col-form-label">Imię</label>
                    <div class="col-8">
                        <input value="{{ $customer->name }}" id="customer_name" name="customer_name" type="text" class="form-control" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="surname" class="col-4 col-form-label">Nazwisko</label>
                    <div class="col-8">
                        <input value="{{ $customer->surname }}" id="surname" name="surname" type="text" class="form-control" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="address" class="col-4 col-form-label">Adres</label>
                    <div class="col-8">
                        <input value="{{ $customer->address }}" id="address" name="address" type="text" class="form-control" disabled>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="phone" class="col-4 col-form-label">Telefon</label>
                    <div class="col-8">
                        <input value="{{ $customer->phone }}" id="phone" name="phone" type="text" class="form-control" disabled>
                    </div>
                </div>
            </div>
        </div>

        <hr />

        <div class="row">
            <div class="col-md-12 mt-3">
                <legend>Lista leków dodanych do wizyty</legend>
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
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($visit_medicals ?? [] as $visit_medical)
                            @php $sum_all_medicals += (int)$visit_medical->quantity*$visit_medical->gross_price; @endphp
                            <tr>
                                <td>{{ $counter_visit_medicals++ }}.</td>
                                <td>{{ $visit_medical->medical->name }}</td>
                                <td class="text-right">{{ $visit_medical->net_price }}</td>
                                <td class="text-right">{{ $visit_medical->gross_price }}</td>
                                <td class="text-center">{{ $visit_medical->vat->name }}</td>
                                <td class="text-center">{{ $visit_medical->quantity }} {{ $visit_medical->medical->unit_measure->short_name }}</td>
                                <td class="text-right">{{ Str::currency((int)$visit_medical->quantity*$visit_medical->gross_price) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row my-5">
                    <div class="col-md-12 text-right cost-summary">
                        <h4>
                            <div class="spinner-grow" role="status">
                                <span class="visually-hidden"></span>
                            </div> Koszt leków weterynaryjnych: {{ Str::currency($sum_all_medicals) }} PLN
                        </h4>
                    </div>
                </div>
                @else
                <hr />
                <div class="text-center my-3">
                    <h4 class="text-info">Wizyta nie zawiera żadnych leków weterynaryjnych.</h4>
                    <h1><i class="fas fa-ban"></i></h1>
                    <a href="{{ route('visits.step2', ['id'=>$visit->id]) }}" class="btn btn-primary">
                        Dodaj lek weterynaryjny
                    </a>
                </div>
                @endif

            </div>
        </div>

        <hr />

        <div class="row">
            <div class="col-md-12 mt-3">
                <legend>Lista usług dodatkowych dodanych do wizyty</legend>
                @if(count($visit_additional_services) > 0)
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
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach($visit_additional_services ?? [] as $visit_additional_service)
                            @php $sum_service = number_format($visit_additional_service->quantity*$visit_additional_service->gross_price,2); $sum_all_additional_services += $sum_service; @endphp
                            <tr>
                                <td>{{ $counter_visit_services++ }}.</td>
                                <td>{{ $visit_additional_service->additionalservice->name }}</td>
                                <td class="text-right">{{ $visit_additional_service->net_price }}</td>
                                <td class="text-right">{{ $visit_additional_service->gross_price }}</td>
                                <td class="text-center">{{ $visit_additional_service->vat->name }}</td>
                                <td class="text-center">{{ $visit_additional_service->quantity }}</td>
                                <td class="text-right">{{ $sum_service }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row my-5">
                    <div class="col-md-12 text-right">
                        <h4>
                            <div class="spinner-grow" role="status">
                                <span class="visually-hidden"></span>
                            </div> Koszt usług dodatkowych: {{ Str::currency($sum_all_additional_services) }} PLN
                        </h4>
                    </div>
                </div>
                @else
                <hr />
                <div class="text-center my-3">
                    <h4 class="text-info">Wizyta nie zawiera żadnych usług dodatkowych.</h4>
                    <h1><i class="fas fa-ban"></i></h1>
                    <a href="{{ route('visits.step3', ['id'=>$visit->id]) }}" class="btn btn-primary">
                        Dodaj usługę
                    </a>
                </div>
                @endif
            </div>
        </div>

        <div class="row my-5">
            <div class="col-md-12 text-center">
                <h4 class="alert alert-info">Koszt wizyty: {{ Str::currency($sum_all_medicals+$sum_all_additional_services) }} PLN</h4>
                <p>Ważna! Kliknięcie przycisku Zapisz wizytę oznacza ostateczne zatwierdzenie wizyty lekarskiej.</p>
                <p class="text-warning">Edycja wizyty na Twoim koncie możliwa max w czasie {{ $maxTimeToEdit }} minut od zapisania wizyty. </p>
            </div>
        </div>
        @if($canManage)
        <form id="save-summary-form" action="{{ route('visits.store_summary', ['id'=>$visit->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="description" class="form-label">Dodatkowe informacje do wizyty</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ $visit->description }}</textarea>
            </div>
            <div class="row mt-4 mb-1">
                <div class="col-md-12">
                    <a href="{{ route('visits.step3', ['id'=>$visit->id]) }}" class="btn btn-success float-left" title="Krok 3 - dodawanie usług">Krok 3 - usługi dodatkowe</a>
                    <button type="submit" class="btn btn-primary float-right" onclick="return confirm('Czy na pewno chcesz zapisać i zakończyć wizytę?')">{{ __('Zapisz wizytę') }} <i class="fas fa-chevron-right"></i></button>

                </div>
            </div>
        </form>
        @endif

    </div>
</div>
@endsection

@include('helpers.sections.format_price')