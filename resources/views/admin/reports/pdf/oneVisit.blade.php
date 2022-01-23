<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>@yield('title', 'Raport dotyczący wizyty nr ' . $visit->visit_number)</title>
</head>

<body class="antialiased">
    @include('admin.reports.pdf.css')

    <h2 class="text-center mb-5">Wizyta lekarska nr {{ $visit->visit_number}} z dnia {{ $visit->visit_date}}</h2>
    <br /><br />
    <div>
        <table width="100%" cellspacing="0">
            <tr>
                <th class="text-center">Data wizyty</th>
                <th class="text-center">Lekarz</th>
                <th class="text-right">SUMA PLN</th>
            </tr>
            <tr>
                <td class="text-center">{{ $visit['visit_date'] }}</td>
                <td class="text-center">{{ $visit['user']['name'] }} {{ $visit['user']['surname'] }}</td>
                <td class="text-right">{{ Str::currency($visit->visit_medicals->sum('sum_gross_price')+$visit->additional_services->sum('sum_gross_price')) }}</td>
            </tr>
        </table>
        <br /><br />
        <h3>LEKI WETERYNARYJNE</h3>
        <table width="100%" cellspacing="0">
            <tr>
                <th>Nazwa</th>
                <th class="text-center">Ilość</th>
                <th class="text-right">Cena jedn.</th>
                <th class="text-right">Do zapłaty</th>
            </tr>
            @foreach($visit->visit_medicals as $visit_medical)
            <tr>
                <td>{{ $visit_medical->medical->name }}</td>
                <td class="text-center">{{ $visit_medical->quantity }}</td>
                <td class="text-right">{{ Str::currency($visit_medical->gross_price) }}</td>
                <td class="text-right">{{ Str::currency($visit_medical->sum_gross_price) }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="3" class="text-right">RAZEM</td>
                <td class="text-right">{{ Str::currency($visit->visit_medicals->sum('sum_gross_price')) }}</td>
            </tr>
        </table>

        <br /><br />
        <h3>USŁUGI</h3>
        <table width="100%" cellspacing="0">
            <tr>
                <th>Nazwa</th>
                <th class="text-center">Ilość</th>
                <th class="text-right">Cena jedn.</th>
                <th class="text-right">Suma PLN</th>
            </tr>
            @foreach($visit->additional_services as $service)
            <tr>
                <td>{{ $service->additionalservice->name }}</td>
                <td class="text-center">{{ $service->quantity }}</td>
                <td class="text-right">{{ Str::currency($service->gross_price) }}</td>
                <td class="text-right">{{ Str::currency($service->sum_gross_price) }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="3" class="text-right">RAZEM</td>
                <td class="text-right">{{ Str::currency($visit->additional_services->sum('sum_gross_price')) }}</td>
            </tr>
        </table>
        <br />

        <table width="100%" cellspacing="0">
            <tr>
                <th class="text-right">Do zapłaty PLN</th>
                <th class="text-right">Zapłacono PLN</th>
                <th class="text-right">Pozostało PLN</th>
            </tr>
            <tr>
                <td class="text-right">{{ Str::currency($visit->gross_price) }}</td>
                <td class="text-right">{{ Str::currency($visit->paid_gross_price) }}</td>
                <td class="text-right">{{ Str::currency($visit->gross_price-$visit->paid_gross_price) }}</td>
            </tr>
        </table>
    </div>

</body>

</html>