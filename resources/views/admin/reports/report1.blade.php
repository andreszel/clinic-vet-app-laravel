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