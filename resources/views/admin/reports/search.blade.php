<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Wyszukiwarka wizyt lekarskich</h6>
    </div>
    <div class="card-body">
        <form id="report-form" action="{{ route('reports.list') }}" method="GET">
            @csrf
            <div class="form-row">
                <div class="col-auto mb-2">
                    <label for="user_id">Lekarz</label>
                    <select name="user_id" id="user_id" class="form-control">
                        <option value="0" @if(!$user_id) selected="selected" @endif>Wybierz</option>
                        @foreach($users ?? [] as $user)
                        <option value="{{ $user->id }}" @if($user->id == $user_id) selected="selected" @endif )>{{ $user->name }} {{ $user->surname }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto mb-2">
                    <label for="from_date">Data od</label>
                    <input value="{{ $from_date }}" type="text" id="from_date" name="from_date" placeholder="Wybierz datę" class="form-control visit-date visit-report-date" autocomplete="off">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
                <div class="col-auto mb-2">
                    <label for="to_date">Data do</label>
                    <input value="{{ $to_date }}" type="text" id="to_date" name="to_date" placeholder="Wybierz datę" class="form-control visit-date visit-report-date" autocomplete="off">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
                <div class="col-auto mb-2">
                    <label for="customer_name">Klient imię</label>
                    <input value="{{ $customer_name }}" type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Wpisz imię Klienta" autocomplete="off">
                </div>
                <div class="col-auto mb-2">
                    <label for="customer_surname">Klient nazwisko</label>
                    <input value="{{ $customer_surname }}" type="text" class="form-control" id="customer_surname" name="customer_surname" placeholder="Wpisz nazwisko Klienta" autocomplete="off">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <p class="text-info">
                        <small>Znalezionych wizyt lekarskich: {{ count($visits) }}</small>
                    </p>
                </div>
                <div class="col-md-6 text-right">
                    <button type="submit" class="btn btn-sm btn-primary">Szukaj wizyt</button>
                </div>
            </div>
        </form>
    </div>
</div>

<hr />