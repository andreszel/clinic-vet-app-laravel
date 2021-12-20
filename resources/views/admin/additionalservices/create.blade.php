@extends('layouts.admin')

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Nowa usługa</h1>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Formularz</h6>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('additionalservices.store') }}">
            @csrf
            <div class="form-group row">
                <label for="name" class="col-md-3 col-form-label">Nazwa usługi</label>
                <div class="col-md-9">
                    <input value="{{ old('name') }}" id="name" name="name" placeholder="Wpisz nazwę usługi" type="text" aria-describedby="nameHelpBlock" class="form-control" required="required">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="gross_price" class="col-3 col-form-label">Cena brutto</label>
                <div class="col-2">
                    <div class="input-group">
                        <input value="{{ old('gross_price') }}" id="gross_price" name="gross_price" placeholder="Wpisz cenę brutto" type="text" class="form-control">
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
                <label class="col-md-3" for="vat_id">Vat sprzedaży</label>
                <div class="col-md-1">
                    <div class="input-group">
                        <select id="vat_id" name="vat_id" required="required" class="custom-select">
                            @foreach($vats as $vat)
                            <option value="{{ $vat->id }}" {{ $vat->id == 2 ? 'selected="selected"' : '' }}>{{ $vat->name }}</option>
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
                            <input name="set_price_in_visit" id="set_price_in_visit_0" type="radio" required="required" class="custom-control-input" value="1">
                            <label for="set_price_in_visit_0" class="custom-control-label">Tak</label>
                        </div>
                    </div>
                    <div class="custom-controls-stacked">
                        <div class="custom-control custom-radio">
                            <input name="set_price_in_visit" id="set_price_in_visit_1" type="radio" required="required" class="custom-control-input" value="0" checked="checked">
                            <label for="set_price_in_visit_1" class="custom-control-label">Nie</label>
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
                            <input name="active" id="active_0" type="radio" required="required" class="custom-control-input" value="1" checked="checked">
                            <label for="active_0" class="custom-control-label">Tak</label>
                        </div>
                    </div>
                    <div class="custom-controls-stacked">
                        <div class="custom-control custom-radio">
                            <input name="active" id="active_1" type="radio" required="required" class="custom-control-input" value="0">
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
                <label for="description" class="col-3 col-form-label">Dodatkowe informacje</label>
                <div class="col-9">
                    <textarea id="description" name="description" placeholder="Miejsce na Twoją notatkę" type="text" class="form-control">{{ old('description') }}</textarea>
                </div>
                @error('description')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label">Dojazd</label>
                <div class="col-md-9">
                    <div class="custom-controls-stacked">
                        <div class="custom-control custom-radio">
                            <input name="drive_to_customer" id="drive_to_customer_0" type="radio" required="required" class="custom-control-input" value="1">
                            <label for="drive_to_customer_0" class="custom-control-label">Tak</label>
                        </div>
                    </div>
                    <div class="custom-controls-stacked">
                        <div class="custom-control custom-radio">
                            <input name="drive_to_customer" id="drive_to_customer_1" type="radio" required="required" class="custom-control-input" value="0" checked="checked">
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
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@include('helpers.sections.medical_prices');