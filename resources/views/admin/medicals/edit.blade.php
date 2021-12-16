@extends('layouts.admin')

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Edycja leku</h1>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Formularz</h6>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('medicals.update', ['id'=>$medical->id]) }}">
            @csrf
            @method('PUT')
            <div class="form-group row">
                <label for="name" class="col-md-3 col-form-label">Nazwa leku</label>
                <div class="col-md-9">
                    <input value="{{ $medical->name }}" id="name" name="name" placeholder="Wpisz nazwę leku" type="text" aria-describedby="nameHelpBlock" class="form-control" required="required">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <!-- ZAKUP -->
            <div class="form-group row">
                <label for="net_price_buy" class="col-3 col-form-label">Cena zakupu netto</label>
                <div class="col-2">
                    <div class="input-group">
                        <input value="{{ $medical->net_price_buy }}" id="net_price_buy" onChange="if(this.value=='NaN' || this.value==''){this.value='0.00'};this.value=parseFloat(this.value.replace(',','.')).toFixed(2);" name="net_price_buy" placeholder="Wpisz cenę netto" type="text" class="form-control">
                        <div class="input-group-append">
                            <div class="input-group-text">PLN</div>
                        </div>
                    </div>
                    @error('net_price_buy')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3" for="vat_buy_id">Vat zakupu</label>
                <div class="col-md-1">
                    <div class="input-group">
                        <select id="vat_buy_id" name="vat_buy_id" required="required" class="custom-select">
                            @foreach($vats as $vat)
                            <option value="{{ $vat->id }}" {{ $medical->vat_buy_id == $vat->id ? 'selected="selected"' : '' }}>{{ $vat->name }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <div class="input-group-text">%</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="gross_price_buy" class="col-3 col-form-label">Cena zakupu brutto</label>
                <div class="col-2">
                    <div class="input-group">
                        <input value="{{ $medical->gross_price_buy }}" id="gross_price_buy" onChange="if(this.value=='NaN' || this.value==''){this.value='0.00'};this.value=parseFloat(this.value.replace(',','.')).toFixed(2);" name="gross_price_buy" placeholder="Wpisz cenę brutto" type="text" class="form-control">
                        <div class="input-group-append">
                            <div class="input-group-text">PLN</div>
                        </div>
                    </div>
                    @error('gross_price_buy')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <!--SPRZEDAŻ-->
            <div class="form-group row">
                <label for="net_price_sell" class="col-3 col-form-label">Cena sprzedaży netto</label>
                <div class="col-2">
                    <div class="input-group">
                        <input value="{{ $medical->net_price_sell }}" id="net_price_sell" onChange="if(this.value=='NaN' || this.value==''){this.value='0.00'};this.value=parseFloat(this.value.replace(',','.')).toFixed(2);" name="net_price_sell" placeholder="Wpisz cenę netto" type="text" class="form-control">
                        <div class="input-group-append">
                            <div class="input-group-text">PLN</div>
                        </div>
                    </div>
                    @error('net_price_sell')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3" for="vat_sell_id">Vat sprzedaży</label>
                <div class="col-md-1">
                    <div class="input-group">
                        <select id="vat_sell_id" name="vat_sell_id" required="required" class="custom-select">
                            @foreach($vats as $vat)
                            <option value="{{ $vat->id }}" {{ $medical->vat_sell_id == $vat->id ? 'selected="selected"' : '' }}>{{ $vat->name }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <div class="input-group-text">%</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="gross_price_sell" class="col-3 col-form-label">Cena sprzedaży brutto</label>
                <div class="col-2">
                    <div class="input-group">
                        <input value="{{ $medical->gross_price_sell }}" id="gross_price_sell" onChange="if(this.value=='NaN' || this.value==''){this.value='0.00'};this.value=parseFloat(this.value.replace(',','.')).toFixed(2);" name="gross_price_sell" placeholder="Wpisz cenę brutto" type="text" class="form-control">
                        <div class="input-group-append">
                            <div class="input-group-text">PLN</div>
                        </div>
                    </div>
                    @error('gross_price_sell')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3" for="unit_measure_id">Jednostka miary</label>
                <div class="col-md-1">
                    <select id="unit_measure_id" name="unit_measure_id" required="required" class="custom-select">
                        @foreach($unit_measures as $unit_measure)
                        <option value="{{ $unit_measure->id }}" {{ $medical->unit_measure_id == $unit_measure->id ? 'selected="selected"' : '' }}>{{ $unit_measure->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label">Lek widoczny dla lekarza</label>
                <div class="col-md-9">
                    <div class="custom-controls-stacked">
                        <div class="custom-control custom-radio">
                            <input name="active" id="active_0" type="radio" required="required" class="custom-control-input" value="1" {{ $medical->active ? 'checked="checked"' : '' }}>
                            <label for="active_0" class="custom-control-label">Tak</label>
                        </div>
                    </div>
                    <div class="custom-controls-stacked">
                        <div class="custom-control custom-radio">
                            <input name="active" id="active_1" type="radio" required="required" class="custom-control-input" value="0" {{ $medical->active == false ? 'checked="checked"' : '' }}>
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
                <label class="col-md-3" for="unit_measure_id">Marża netto</label>
                <div class="col-md-2">
                    <div class="input-group">
                        <input value="{{ $medical->net_margin }}" id="net_margin" type="text" class="form-control" disabled>
                        <div class="input-group-append">
                            <div class="input-group-text">PLN</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3" for="unit_measure_id">Marża brutto</label>
                <div class="col-md-2">
                    <div class="input-group">
                        <input value="{{ $medical->gross_margin }}" id="gross_margin" type="text" class="form-control" disabled>
                        <div class="input-group-append">
                            <div class="input-group-text">PLN</div>
                        </div>
                    </div>
                </div>
            </div>

            @if($errors->any())
            {!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
            @endif

            <div class="form-group row">
                <div class="offset-md-3 col-md-9">
                    <button name="submit" type="submit" class="btn btn-primary">Zapisz</button>
                    <a href="{{ route('medicals.list') }}" class="btn btn-success float-right">Lista leków</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@include('helpers.sections.medical_prices');