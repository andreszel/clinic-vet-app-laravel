<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold">Wyszukiwarka leków</h6>
    </div>
    <div class="card-body">
        <form id="report-form" class="form-inline" action="{{ route('medicals.list') }}" method="GET">
            @csrf
            <label for="name" class="mr-2">Nazwa leku</label>
            <input value="{{ $name }}" type="text" class="form-control mr-2" id="name" name="name" placeholder="Wpisz nazwę leku" autocomplete="off">

            <button type="submit" class="btn btn-sm btn-primary">Szukaj</button>
        </form>
    </div>
</div>

<hr />