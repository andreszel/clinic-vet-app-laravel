@extends('layouts.admin')

@section('content')
<!-- Page Heading -->
<div class="row">
    <div class="cold-md-12 mb-3">
        <h1 class="h3 text-gray-800 d-inline">Nowa wizyta dla {{ $customer->name }} {{ $customer->surname }} - dodawanie usług dodatkowych</h1>
        @include('helpers.sections.info_form_add_edit_visit')
    </div>
</div>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><span class="text-uppercase">krok</span> {{ $currentStep }}/{{ $maxStep }}</h6>
    </div>
    <div class="card-body">
        <form id="visit-form" action="{{ route('visits.store_step1', ['id'=>$visit->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="customer_id" value="{{ $customer->id }}" />
            <legend>Dane Klienta</legend>
            <div class="form-group row">
                <label for="customer_name" class="col-4 col-form-label">Imię</label>
                <div class="col-8">
                    <input value="{{ $customer->name }}" id="customer_name" name="customer_name" type="text" class="form-control" disabled>
                </div>
            </div>
            <div class="form-group row">
                <label for="surname" class="col-4 col-form-label">Nazwisko</label>
                <div class="col-8">
                    <input value="{{ $customer->surname }}" id="surname" name="surname" type="text" class="form-control" disabled>
                </div>
            </div>
            <div class="form-group row">
                <label for="address" class="col-4 col-form-label">Adres</label>
                <div class="col-8">
                    <input value="{{ $customer->address }}" id="address" name="address" type="text" class="form-control" disabled>
                </div>
            </div>
            <div class="form-group row">
                <label for="phone" class="col-4 col-form-label">Telefon</label>
                <div class="col-8">
                    <input value="{{ $customer->phone }}" id="phone" name="phone" type="text" class="form-control" disabled>
                </div>
            </div>
            <legend>Pozostałe dane</legend>
            <div class="form-group row">
                <label class="col-4">Typ płatności</label>
                <div class="col-8">
                    <select id="pay_type_id" name="pay_type_id" required="required" class="custom-select">
                        @foreach($pay_types as $pay_type)
                        <option value="{{ $pay_type->id }}" {{ $visit->pay_type_id == $pay_type->id ? 'selected="selected"' : '' }}>{{ $pay_type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="visit_date" class="col-4 col-form-label">Data wizyty</label>
                <div class="col-1">
                    <input value="{{ $visit->visit_date }}" type="text" id="visit_date" name="visit_date" placeholder="Wpisz datę wizyty" class="form-control visit-date visit-report-date" required="required" autocomplete="off">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-4 col-form-label">Taryfa nocna</label>
                <div class="col-md-8">
                    <div class="custom-controls-stacked">
                        <div class="custom-control custom-radio">
                            <input name="nightly_visit" id="nightly_visit_0" type="radio" required="required" class="custom-control-input" value="1" {{ $visit->nightly_visit ? 'checked="checked"' : '' }}>
                            <label for="nightly_visit_0" class="custom-control-label">Tak</label>
                        </div>
                    </div>
                    <div class="custom-controls-stacked">
                        <div class="custom-control custom-radio">
                            <input name="nightly_visit" id="nightly_visit_1" type="radio" required="required" class="custom-control-input" value="0" {{ $visit->nightly_visit == false ? 'checked="checked"' : '' }}>
                            <label for="nightly_visit_1" class="custom-control-label">Nie</label>
                        </div>
                    </div>
                    @error('active')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            @if($errors->any())
            {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
            @endif

            <div class="form-group row">
                <div class="offset-4 col-8">
                    @if($currentStep > 1)<button name="prev" type="button" class="btn btn-success float-left"><i class="fas fa-chevron-left"></i> Wstecz</button>@endif
                    @if($currentStep < $maxStep) <button name="next" type="submit" class="btn btn-success float-right">Dalej <i class="fas fa-chevron-right"></i></button>@endif
                        @if($currentStep == $maxStep)<button name="save" type="submit" class="btn btn-success float-right">Zapisz <i class="fas fa-chevron-right"></i></button>@endif
                </div>
            </div>
        </form>

    </div>
</div>
@endsection

@include('helpers.sections.datetime')