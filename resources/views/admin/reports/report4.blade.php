<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold" title="Raport 4">Szczegółowe informacje dot. wizyt lekarzy lub lekarza</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                @if($visit_calc_details)
                @foreach($visit_calc_details as $user_id => $items)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">lek. {{ $items['name'] }} {{ $items['surname'] }}</h6>
                    </div>
                    <div class="card-body">

                        @foreach($items['visits'] as $key => $item)
                        <div class="table-responsive">
                            <table class="table table-bordered bg-dark text-white" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Nr wizyty</th>
                                        <th>Data wizyty</th>
                                        <th>Klient</th>
                                        <th>Koszt brutto</th>
                                        <th>Zysk lekarz</th>
                                        <th>Zysk firma</th>
                                        <th>Typ płatności</th>
                                        <th class="text-center">Raport PDF wizyty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $item['visit_number'] }}</td>
                                        <td>{{ $item['visit_date'] }}</td>
                                        <td>{{ $item['customer']['name'] . ' ' . $item['customer']['surname'] }}</td>
                                        <td class="text-right">{{ $item['gross_price'] }}</td>
                                        <td class="text-right">{{ $item['margin_doctor'] }}</td>
                                        <td class="text-right">{{ $item['margin_company'] }}</td>
                                        <td>{{ $item['pay_type_name'] }}</td>
                                        <td class="text-center" title="Raport dotyczy tej konkretnej wizyty - w pliku znajdują się szczegóły wizyty, wykaz leków, usług dodatkowych">
                                            <a href="{{ route('reports.pdf.one_visit_report', ['id'=>$item['id']]) }}" target="_blank" class="btn btn-primary" title="Podgląd wydruku">
                                                <i class="fas fa-download text-white-50"></i> Pobierz
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                        <h5>Lista leków</h5>

                        <div class="table-responsive">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Lp.</th>
                                            <th>Nazwa leku</th>
                                            <th>Jedn. miary</th>
                                            <th>Ilość</th>
                                            <th>Kwota VAT PLN</th>
                                            <th>Kwota netto PLN</th>
                                            <th>Kwota brutto PLN</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr class="bg-light text-info">
                                            <th colspan="4" class="align-middle text-right text-uppercase">Razem</th>
                                            <td class="text-right">{{ Str::currency($item['sum_vat_price_medical']) }}</td>
                                            <td class="text-right">{{ Str::currency($item['sum_net_price_medical']) }}</td>
                                            <td class="text-right">{{ Str::currency($item['sum_gross_price_medical']) }}</td>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach($item['visit_medicals'] as $key_visit_medicals => $item_visit_medical)
                                        <tr>
                                            <td>{{ $counter++ }}.</td>
                                            <td>{{ $item_visit_medical['medical']['name'] }}</td>
                                            <td>{{ $item_visit_medical['medical']['unit_measure']['name'] }}</td>
                                            <td>{{ $item_visit_medical['quantity'] }}</td>
                                            <td class="text-right">{{ Str::currency($item_visit_medical['sum_gross_price']-$item_visit_medical['sum_net_price']) }}</td>
                                            <td class="text-right">{{ Str::currency($item_visit_medical['sum_net_price']) }}</td>
                                            <td class="text-right">{{ Str::currency($item_visit_medical['sum_gross_price']) }}</td>
                                        </tr>
                                        @endforeach
                                        @php $counter = 1; @endphp
                                    </tbody>
                                </table>
                            </div>
                        </div>

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
                                <tr>
                                <tr class="bg-light text-info">
                                    <th colspan="3" class="align-middle text-right text-uppercase">Razem</th>
                                    <td class="text-right">{{ Str::currency($item['sum_vat_price_additional_service']) }}</td>
                                    <td class="text-right">{{ Str::currency($item['sum_net_price_additional_service']) }}</td>
                                    <td class="text-right">{{ Str::currency($item['sum_gross_price_additional_service']) }}</td>
                                </tr>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach($item['additional_services'] as $key_additional_services => $item_additional_service)
                                <tr>
                                    <td>{{ $counter++ }}.</td>
                                    <td>{{ $item_additional_service['additionalservice']['name'] }}</td>
                                    <td>{{ $item_additional_service['quantity'] }}</td>
                                    <td class="text-right">{{ Str::currency($item_additional_service['sum_gross_price']-$item_additional_service['sum_net_price']) }}</td>
                                    <td class="text-right">{{ Str::currency($item_additional_service['sum_net_price']) }}</td>
                                    <td class="text-right">{{ Str::currency($item_additional_service['sum_gross_price']) }}</td>
                                </tr>
                                @endforeach
                                @php $counter = 1; @endphp
                            </tbody>
                        </table>
                        <hr class="my-4 text-warning" />

                        @endforeach
                    </div>
                </div>
                @endforeach
                @else
                <h3 class="text-info">Brak danych</h3>
                @endif
            </div>
        </div>
    </div>
</div>

<hr />