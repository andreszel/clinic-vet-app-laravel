@extends('layouts.admin')

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Edycja usługi</h1>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Formularz</h6>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('additionalservices.update', ['id'=>$additionalservice->id]) }}">
            @csrf
            @method('PUT')
            <div class="form-group row">
                <label for="name" class="col-md-3 col-form-label">Nazwa usługi</label>
                <div class="col-md-9">
                    <input value="{{ $additionalservice->name }}" id="name" name="name" placeholder="Wpisz nazwę usługi" type="text" aria-describedby="nameHelpBlock" class="form-control" required="required">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <!-- ZAKUP -->
            <div class="form-group row">
                <label for="gross_price" class="col-3 col-form-label">Cena brutto</label>
                <div class="col-2">
                    <div class="input-group">
                        <input value="{{ $additionalservice->gross_price }}" id="gross_price" name="gross_price" placeholder="Wpisz cenę brutto" type="text" class="form-control">
                        <div class="input-group-append">
                            <div class="input-group-text">PLN</div>
                        </div>
                    </div>
                    @error('gross_price')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="nightly_gross_price" class="col-3 col-form-label">Stawka nocna brutto</label>
                <div class="col-2">
                    <div class="input-group">
                        <input value="{{ $additionalservice->nightly_gross_price }}" id="nightly_gross_price" name="nightly_gross_price" placeholder="Wpisz stawkę nocną brutto" type="text" class="form-control">
                        <div class="input-group-append">
                            <div class="input-group-text">PLN</div>
                        </div>
                    </div>
                    @error('nightly_gross_price')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3" for="vat_id">Vat sprzedaży</label>
                <div class="col-md-1">
                    <div class="input-group">
                        <select id="vat_id" name="vat_id" required="required" class="custom-select">
                            @foreach($vats as $vat)
                            <option value="{{ $vat->id }}" {{ $additionalservice->vat_id == $vat->id ? 'selected="selected"' : '' }}>{{ $vat->name }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <div class="input-group-text">%</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label">Cena wpisywana w zamówieniu</label>
                <div class="col-md-9">
                    <div class="custom-controls-stacked">
                        <div class="custom-control custom-radio">
                            <input name="set_price_in_visit" id="set_price_in_visit_1" type="radio" required="required" class="custom-control-input" value="1" {{ $additionalservice->set_price_in_visit == 1 ? 'checked="checked"' : '' }}>
                            <label for="set_price_in_visit_1" class="custom-control-label">Tak</label>
                        </div>
                    </div>
                    <div class="custom-controls-stacked">
                        <div class="custom-control custom-radio">
                            <input name="set_price_in_visit" id="set_price_in_visit_0" type="radio" required="required" class="custom-control-input" value="0" {{ $additionalservice->set_price_in_visit == 0 ? 'checked="checked"' : '' }}>
                            <label for="set_price_in_visit_0" class="custom-control-label">Nie</label>
                        </div>
                    </div>
                    @error('set_price_in_visit')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label">Usługa włączona</label>
                <div class="col-md-9">
                    <div class="custom-controls-stacked">
                        <div class="custom-control custom-radio">
                            <input name="active" id="active_0" type="radio" required="required" class="custom-control-input" value="1" {{ $additionalservice->active ? 'checked="checked"' : '' }}>
                            <label for="active_0" class="custom-control-label">Tak</label>
                        </div>
                    </div>
                    <div class="custom-controls-stacked">
                        <div class="custom-control custom-radio">
                            <input name="active" id="active_1" type="radio" required="required" class="custom-control-input" value="0" {{ $additionalservice->active == false ? 'checked="checked"' : '' }}>
                            <label for="active_1" class="custom-control-label">Nie</label>
                        </div>
                    </div>
                    @error('active')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label">Dojazd</label>
                <div class="col-md-9">
                    <div class="custom-controls-stacked">
                        <div class="custom-control custom-radio">
                            <input name="drive_to_customer" id="drive_to_customer_0" type="radio" required="required" class="custom-control-input" value="1" {{ $additionalservice->drive_to_customer ? 'checked="checked"' : '' }}>
                            <label for="drive_to_customer_0" class="custom-control-label">Tak</label>
                        </div>
                    </div>
                    <div class="custom-controls-stacked">
                        <div class="custom-control custom-radio">
                            <input name="drive_to_customer" id="drive_to_customer_1" type="radio" required="required" class="custom-control-input" value="0" {{ $additionalservice->drive_to_customer == false ? 'checked="checked"' : '' }}>
                            <label for="drive_to_customer_1" class="custom-control-label">Nie</label>
                        </div>
                    </div>
                    @error('drive_to_customer')
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
                <div class="offset-md-3 col-md-9">
                    <button name="submit" type="submit" class="btn btn-primary">Zapisz</button>
                    <a href="{{ route('additionalservices.list') }}" class="btn btn-success float-right">Lista usług</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection