<!-- Raport 1: Statystyka wizyt wszystkich lekarzy -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold" title="Raport 6">Statystyka wizyt Klientów</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            @if($sum_visit_customer_stats)
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th rowspan="2">Lp.</th>
                        <th rowspan="2">Klient</th>
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
                        <th>{{ $sum_visit_customer_stats['last_year'] }}</th>
                        <th>{{ $sum_visit_customer_stats['last_six_months'] }}</th>
                        <th>{{ $sum_visit_customer_stats['last_three_months'] }}</th>
                        <th>{{ $sum_visit_customer_stats['last_month'] }}</th>
                        <th>{{ $sum_visit_customer_stats['last_week'] }}</th>
                        <th>{{ $sum_visit_customer_stats['today'] }}</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($visit_customer_stats as $key => $visit_customer_stat)
                    <tr>
                        <td>{{ $counter++ }}.</td>
                        <td>
                            <p class="float-left">{{ $visit_customer_stat['name'] }} {{ $visit_customer_stat['surname'] }}</p>
                            @if($from_date && $to_date)
                            <a href="{{ route('reports.pdf.customer_report', ['id' => $visit_customer_stat['id'], 'from_date'=>$from_date, 'to_date'=>$to_date]) }}" target="_blank" class="btn btn-primary btn-sm float-right" title="Podgląd wydruku">
                                <i class="fas fa-download text-white-50"></i> Pobierz
                            </a>
                            @endif
                        </td>
                        <td>{{ $visit_customer_stat['stats']['last_year'] }}</td>
                        <td>{{ $visit_customer_stat['stats']['last_six_months'] }}</td>
                        <td>{{ $visit_customer_stat['stats']['last_three_months'] }}</td>
                        <td>{{ $visit_customer_stat['stats']['last_month'] }}</td>
                        <td>{{ $visit_customer_stat['stats']['last_week'] }}</td>
                        <td>{{ $visit_customer_stat['stats']['today'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <h3 class="text-info">Brak danych</h3>
            @endif
        </div>
    </div>
</div>
@php $counter = 1; @endphp

<hr />