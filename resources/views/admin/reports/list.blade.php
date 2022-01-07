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

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Wyszukiwarka wizyt lekarskich</h6>
    </div>
    <div class="card-body">
        <form id="report-form" action="{{ route('reports.list') }}" method="GET">
            @csrf
            <div class="form-row">
                <div class="col-md-2">
                    <label for="user_id">Lekarz</label>
                    <select name="user_id" id="user_id" class="form-control">
                        <option value="0" @if(!$user_id) selected="selected" @endif>Wybierz</option>
                        @foreach($users ?? [] as $user)
                        <option value="{{ $user->id }}" @if($user->id == $user_id) selected="selected" @endif )>{{ $user->name }} {{ $user->surname }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <label for="from_date">Data od</label>
                    <input value="{{ $from_date }}" type="text" id="from_date" name="from_date" placeholder="Wybierz datę" class="form-control visit-date visit-report-date" autocomplete="off">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
                <div class="col-md-1">
                    <label for="to_date">Data do</label>
                    <input value="{{ $to_date }}" type="text" id="to_date" name="to_date" placeholder="Wybierz datę" class="form-control visit-date visit-report-date" autocomplete="off">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
                <div class="col-md-2">
                    <label for="customer_phone">Klient telefon</label>
                    <input value="{{ $customer_phone }}" type="text" class="form-control" id="customer_phone" name="customer_phone" placeholder="Wpisz telefon Klienta" autocomplete="off">
                </div>
                <div class="col-md-2">
                    <label for="customer_name">Klient imię</label>
                    <input value="{{ $customer_name }}" type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Wpisz imię Klienta" autocomplete="off">
                </div>
                <div class="col-md-2">
                    <label for="customer_surname">Klient nazwisko</label>
                    <input value="{{ $customer_surname }}" type="text" class="form-control" id="customer_surname" name="customer_surname" placeholder="Wpisz nazwisko Klienta" autocomplete="off">
                </div>

                <div class="col-md-2">
                    <label for="customer_email">Klient email</label>
                    <input value="{{ $customer_email }}" type="text" class="form-control" id="customer_email" name="customer_email" placeholder="Wpisz email Klienta" autocomplete="off">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12 text-right">
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download text-white-50"></i> Pobierz PDF</a>
                    <button type="submit" class="btn btn-sm btn-primary">Szukaj</button>
                </div>
            </div>
        </form>
    </div>
</div>

<hr />

<!-- Raport 1: Statystyka wizyt wszystkich lekarzy -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Raport 1: Statystyka wizyt wszystkich lekarzy</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th rowspan="2">Lp.</th>
                        <th rowspan="2">Lekarz</th>
                        <th colspan="6">Ilość wizyt za ostatni</th>
                    </tr>
                    <tr>
                        <th>1 rok</th>
                        <th>6 m-cy</th>
                        <th>3 m-ce</th>
                        <th>1 m-c</th>
                        <th>1 tydzień</th>
                        <th>1 dzień</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr class="bg-light text-info">
                        <th colspan="2" class="align-middle text-right text-uppercase">Razem</th>
                        <th>{{ $sum_visit_stats['last_year'] }}</th>
                        <th>{{ $sum_visit_stats['last_six_months'] }}</th>
                        <th>{{ $sum_visit_stats['last_three_months'] }}</th>
                        <th>{{ $sum_visit_stats['last_month'] }}</th>
                        <th>{{ $sum_visit_stats['last_week'] }}</th>
                        <th>{{ $sum_visit_stats['today'] }}</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($visit_stats as $key => $visit_stat)
                    <tr>
                        <td>{{ $counter++ }}.</td>
                        <td>{{ $visit_stat['name'] }} {{ $visit_stat['surname'] }}</td>
                        <td>{{ $visit_stat['stats']['last_year'] }}</td>
                        <td>{{ $visit_stat['stats']['last_six_months'] }}</td>
                        <td>{{ $visit_stat['stats']['last_three_months'] }}</td>
                        <td>{{ $visit_stat['stats']['last_month'] }}</td>
                        <td>{{ $visit_stat['stats']['last_week'] }}</td>
                        <td>{{ $visit_stat['stats']['today'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@php $counter = 1; @endphp

<hr />

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Raport 2: Statystyka leków i usług dodatkowych</h6>
    </div>
    <div class="card-body">

        <h5>Lista leków</h5>

        <div class="table-responsive">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Lp.</th>
                            <th>Nazwa leku</th>
                            <th>Ilość</th>
                            <th>Jedn. miary</th>
                            <th>Kwota VAT PLN</th>
                            <th>Kwota netto PLN</th>
                            <th>Kwota brutto PLN</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="bg-light text-info">
                            <th colspan="4" class="align-middle text-right text-uppercase">Razem</th>
                            <th class="text-right">{{ Str::currency($medical_stats_vat_price_sum) }}</th>
                            <th class="text-right">{{ Str::currency($medical_stats_net_price_sum) }}</th>
                            <th class="text-right">{{ Str::currency($medical_stats_gross_price_sum) }}</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($medical_stats AS $key => $medical_stat)
                        <tr>
                            <td>{{ $counter++ }}.</td>
                            <td>{{ $medical_stat['name'] }}</td>
                            <td class="text-right">{{ $medical_stat['quantity'] }}</td>
                            <td>{{ $medical_stat['unit_measure_name'] }}</td>
                            <td class="text-right">{{ Str::currency($medical_stat['vat_price']) }}</td>
                            <td class="text-right">{{ Str::currency($medical_stat['net_price']) }}</td>
                            <td class="text-right">{{ Str::currency($medical_stat['gross_price']) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @php $counter = 1; @endphp

        <h5>Lista usług</h5>

        <table class="table table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Lp.</th>
                    <th>Nazwa</th>
                    <th>Ilość</th>
                    <th>Kwota VAT PLN</th>
                    <th>Kwota netto PLN</th>
                    <th>Kwota brutto PLN</th>
                </tr>
            </thead>
            <tfoot>
                <tr class="bg-light text-info">
                    <th colspan="3" class="align-middle text-right text-uppercase">Razem</th>
                    <th class="text-right">{{ Str::currency($additional_service_stats_vat_price_sum) }}</th>
                    <th class="text-right">{{ Str::currency($additional_service_stats_net_price_sum) }}</th>
                    <th class="text-right">{{ Str::currency($additional_service_stats_gross_price_sum) }}</th>
                </tr>
            </tfoot>
            <tbody>
                @foreach($additional_service_stats AS $additional_service_stat)
                <tr>
                    <td>{{ $counter++ }}.</td>
                    <td>{{ $additional_service_stat['name'] }}</td>
                    <td>{{ $additional_service_stat['quantity'] }}</td>
                    <td class="text-right">{{ Str::currency($additional_service_stat['vat_price']) }}</td>
                    <td class="text-right">{{ Str::currency($additional_service_stat['net_price']) }}</td>
                    <td class="text-right">{{ Str::currency($additional_service_stat['gross_price']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @php $counter = 1; @endphp

        <div class="row my-5">
            <div class="col-md-12 text-right cost-summary">
                <h4>
                    <div class="spinner-grow" role="status">
                        <span class="visually-hidden"></span>
                    </div> Koszt leków weterynaryjnych i usług dodatkowych: {{ Str::currency($services_medicals_stats_gross_price_sum) }} PLN
                </h4>
            </div>
        </div>
    </div>
</div>

<hr />

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Raport 3: Statystyka obrotów i zysku</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Lp.</th>
                        <th class="col-md-4">Lekarz</th>
                        <th>Obroty leki PLN</th>
                        <th>Obroty usługi dodatkowe PLN</th>
                        <th>Obroty PLN</th>
                        <th>Zysk firma PLN</th>
                        <th>Zysk lekarz PLN</th>
                        <th>Zyski suma PLN</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr class="bg-light text-info">
                        <th colspan="2" class="align-middle text-right text-uppercase">Razem</th>
                        <th class="text-right">{{ Str::currency($turnover_margin_stats_sum['medicals_turnover']) }}</th>
                        <th class="text-right">{{ Str::currency($turnover_margin_stats_sum['additional_services_turnover']) }}</th>
                        <th class="text-right">{{ Str::currency($turnover_margin_stats_sum['turnover']) }}</th>
                        <th class="text-right">{{ Str::currency($turnover_margin_stats_sum['margin_company']) }}</th>
                        <th class="text-right">{{ Str::currency($turnover_margin_stats_sum['margin_doctor']) }}</th>
                        <th class="text-right">{{ Str::currency($turnover_margin_stats_sum['margin_all']) }}</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($turnover_margin_stats as $key => $item)
                    <tr>
                        <td>{{ $counter++ }}.</td>
                        <td>{{ $item['name'] }} {{ $item['surname'] }}</td>
                        <td class="text-right">{{ Str::currency($item['medicals_turnover']) }}</td>
                        <td class="text-right">{{ Str::currency($item['additional_services_turnover']) }}</td>
                        <td class="text-right">{{ Str::currency($item['turnover']) }}</td>
                        <td class="text-right">{{ Str::currency($item['margin_company']) }}</td>
                        <td class="text-right">{{ Str::currency($item['margin_doctor']) }}</td>
                        <td class="text-right">{{ Str::currency($item['margin_all']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@php $counter = 1; @endphp

<hr />

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Raport 4. Link do Szczegółowe rozliczenie wizyt wszystkich lekarzy</h6>
    </div>
    <div class="card-body">
        Raport w osobnym linku, ponieważ jest to długa lista - <a href="raport-4.html" target="_blank" class="text-primary">Pokaż raport</a>.
    </div>
</div>

<hr />

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Raport 5. Rozliczenie wizyt</h6>
    </div>
    <div class="card-body">
        @if(count($visits) > 0)

        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nr wizyty</th>
                        <th>Data wizyty</th>
                        <th>Klient</th>
                        <th>Lekarz</th>
                        <th>Koszt brutto</th>
                        <th>Zysk lekarz</th>
                        <th>Zysk firma</th>
                        <th>Typ płatności</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($visit_stats_by_pay_type as $key => $visit)
                    <tr>
                        <td>{{ $counter++ }}.</td>
                        <td>{{ $visit['visit_date'] }}</td>
                        <td>{{ $visit['customer_name'] . ' ' . $visit['customer_surname'] }}</td>
                        <td>{{ $visit['user_name'] . ' ' . $visit['user_surname'] }}</td>
                        <td class="text-right">{{ $visit['gross_price'] }}</td>
                        <td class="text-right">{{ $visit['margin_doctor'] }}</td>
                        <td class="text-right">{{ $visit['margin_company'] }}</td>
                        <td>{{ $visit['pay_type_name'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    @foreach($summary_visit_stats_by_pay_type as $key => $item)
                    <tr class="bg-light text-info">
                        @if($key == 0)
                        <th colspan="4" rowspan="{{ $count_summary_visit_stats_by_pay_type }}" class="align-middle text-center text-uppercase">Razem</th>
                        @endif
                        <th class="text-right">{{ $item['gross_price'] }}</th>
                        <th class="text-right">{{ $item['margin_company'] }}</th>
                        <th class="text-right">{{ $item['margin_doctor'] }}</th>
                        <th>{{ $item['pay_type_name'] }}</th>
                    </tr>
                    @endforeach
                </tfoot>
            </table>
        </div>
        @endif
    </div>
</div>
@php $counter = 1; @endphp

@endsection

@include('helpers.sections.datatables')
@include('helpers.sections.datetime')