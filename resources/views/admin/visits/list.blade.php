@extends('layouts.admin')

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Wizyty lekarskie</h1>



<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Lista</h6>
    </div>
    <div class="card-body">

        @if(!$visits->isEmpty())
        <div class="col-xl-12 form-group" id="validation-message-additionalservice"></div>

        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Lp.</th>
                        <th>Klient</th>
                        <th>Data wizyty</th>
                        <th>Cena brutto [PLN]</th>
                        <th>Lekarz</th>
                        <th>Wizyta potwierdzona</th>
                        <th>Data potwierdzenia</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Lp.</th>
                        <th>Klient</th>
                        <th>Data wizyty</th>
                        <th>Cena brutto [PLN]</th>
                        <th>Lekarz</th>
                        <th>Wizyta potwierdzona</th>
                        <th>Data potwierdzenia</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($visits ?? [] as $visit)
                    <tr class="{{ $visit->confirm_visit ? 'text-success' : 'text-warning' }}">
                        <td>{{ $counter++ }}.</td>
                        <td>{{ $visit->customer->name }} {{ $visit->customer->surname }}</td>
                        <td>{{ $visit->visit_date }}</td>
                        <td class="text-right">{{ $visit->gross_price }}</td>
                        <td>{{ $visit->user->name }} {{ $visit->user->surname }}</td>
                        <td>{{ $visit->confirm_visit ? 'tak' : 'nie' }}</td>
                        <td>{{ $visit->confirm_visit ? $visit->updated_at : '' }}</td>
                        <td>
                            <form action="{{ route('visits.remove', ['id' => $visit->id]) }}" method="post">
                                @method('DELETE')
                                {{ csrf_field() }}
                                <a href="#" class="btn text-primary" title="Podgląd wydruku">
                                    <i class="far fa-file-pdf"></i>
                                </a>
                                <a href="{{ route('visits.summary', ['id' => $visit->id]) }}" class="btn text-primary" title="Szczegóły wizyty">
                                    <i class="fas fa-info"></i>
                                </a>
                                @if($visitRepository->canManageVisit($visit->id))
                                <a href="{{ route('visits.edit', ['id' => $visit->id]) }}" class="btn btn-sm text-primary mr-2" title="Edytuj wizytę">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="submit" class="btn btn-sm text-danger mr-2" onclick="return confirm('Czy na pewno chcesz usunąć?')"><i class="fas fa-trash-alt"></i></button>
                                @endif
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center my-3">
            <h4 class="text-info">Lista jest pusta</h4>
            <h1><i class="fas fa-ban"></i></h1>
            <p>Chcąc dodać nową wizytę lekarską należy znaleźć lub dodać Klienta w zakładce <a href="{{ route('customers.list') }}">Klienci kliniki</a> i kliknąć w ikonkę <i class="fas fa-fw fa-briefcase-medical"></i> przy Kliencie. :)</p>
        </div>
        @endif
    </div>
</div>

@endsection

@include('helpers.sections.datatables')