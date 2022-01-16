@extends('layouts.admin')

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Nowy klient</h1>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Formularz</h6>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('customers.store') }}">
            @csrf
            <div class="form-group row">
                <label for="name" class="col-md-3 col-form-label">Imię</label>
                <div class="col-md-9">
                    <input value="{{ old('name') }}" id="name" name="name" placeholder="Wpisz imię" type="text" aria-describedby="nameHelpBlock" class="form-control" required="required">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="surname" class="col-md-3 col-form-label">Nazwisko</label>
                <div class="col-md-9">
                    <input value="{{ old('surname') }}" id="surname" name="surname" placeholder="Wpisz nazwisko" type="text" aria-describedby="nameHelpBlock" class="form-control" required="required">
                    @error('surname')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="number_herd" class="col-md-3 col-form-label">Numer stada</label>
                <div class="col-md-9">
                    <input value="{{ old('number_herd') }}" id="number_herd" name="number_herd" placeholder="Wpisz numer stada" type="text" aria-describedby="nameHelpBlock" class="form-control" required="required">
                    @error('number_herd')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="nip" class="col-md-3 col-form-label">NIP</label>
                <div class="col-md-9">
                    <input value="{{ old('nip') }}" id="nip" name="nip" placeholder="Wpisz NIP" type="text" aria-describedby="nameHelpBlock" class="form-control" required="required">
                    @error('nip')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="address" class="col-md-3 col-form-label">Adres</label>
                <div class="col-md-9">
                    <input value="{{ old('address') }}" id="address" name="address" placeholder="Wpisz adres" type="text" class="form-control">
                    @error('address')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="phone" class="col-md-3 col-form-label">Telefon</label>
                <div class="col-md-9">
                    <input value="{{ old('phone') }}" id="phone" name="phone" placeholder="Wpisz telefon" type="text" class="form-control">
                    @error('phone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="email" class="col-md-3 col-form-label">E-mail</label>
                <div class="col-md-9">
                    <input value="{{ old('email') }}" id="email" name="email" placeholder="Wpisz email" type="text" class="form-control">
                    @error('email')
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