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