<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Raport 3: Statystyka obrotów i zysku</h6>
    </div>
    <div class="card-body">
        @if($turnover_margin_stats_sum)
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
        @else
        <h3 class="text-info">Brak danych</h3>
        @endif
    </div>
</div>
@php $counter = 1; @endphp

<hr />