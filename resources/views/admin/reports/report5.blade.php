<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold" title="Raport 5">Rozliczenie wizyt</h6>
    </div>
    <div class="card-body">
        @if(count($visits) > 0)
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Numer wizyty</th>
                        <th>Data wizyty</th>
                        <th>Klient</th>
                        <th>Lekarz</th>
                        <th>Raport PDF</th>
                        <th>Koszt brutto</th>
                        <th>Zysk lekarz</th>
                        <th>Zysk firma</th>
                        <th>Typ płatności</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($visit_stats_by_pay_type as $key => $visit)
                    <tr>
                        <td>{{ $visit['visit_number'] }}</td>
                        <td>{{ $visit['visit_date'] }}</td>
                        <td>{{ $visit['customer_name'] . ' ' . $visit['customer_surname'] }}</td>
                        <td>{{ $visit['user_name'] . ' ' . $visit['user_surname'] }}</td>
                        <td>
                            <a href="{{ route('reports.pdf.one_visit_report', ['id'=>$visit['id']]) }}" target="_blank" class="btn btn-sm btn-primary" title="Podgląd wydruku">
                                <i class="fas fa-download text-white-50"></i> Pobierz
                            </a>
                        </td>
                        <td class="text-right">{{ Str::currency($visit['gross_price']) }}</td>
                        <td class="text-right">{{ Str::currency($visit['margin_doctor']) }}</td>
                        <td class="text-right">{{ Str::currency($visit['margin_company']) }}</td>
                        <td>{{ $visit['pay_type_name'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    @foreach($summary_visit_stats_by_pay_type as $key => $item)
                    <tr class="bg-light text-info">
                        @if($key == 0)
                        <th colspan="5" rowspan="{{ $count_summary_visit_stats_by_pay_type }}" class="align-middle text-center text-uppercase">Razem</th>
                        @endif
                        <th class="text-right">{{ Str::currency($item['gross_price']) }}</th>
                        <th class="text-right">{{ Str::currency($item['margin_doctor']) }}</th>
                        <th class="text-right">{{ Str::currency($item['margin_company']) }}</th>
                        <th>{{ $item['pay_type_name'] }}</th>
                    </tr>
                    @endforeach
                </tfoot>
            </table>
        </div>
        @else
        <h3 class="text-info">Brak danych</h3>
        @endif
    </div>
</div>
@php $counter = 1; @endphp