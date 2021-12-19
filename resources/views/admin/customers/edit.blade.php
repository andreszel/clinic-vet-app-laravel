@extends('layouts.admin')

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Edycja klienta</h1>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Formularz</h6>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('customers.update', ['id'=>$customer->id]) }}">
            @csrf
            @method('PUT')
            <div class="form-group row">
                <label for="name" class="col-md-3 col-form-label">Imię</label>
                <div class="col-md-9">
                    <input value="{{ $customer->name }}" id="name" name="name" placeholder="Wpisz imię" type="text" aria-describedby="nameHelpBlock" class="form-control" required="required">
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
                    <input value="{{ $customer->surname }}" id="surname" name="surname" placeholder="Wpisz nazwisko" type="text" aria-describedby="nameHelpBlock" class="form-control" required="required">
                    @error('surname')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="address" class="col-md-3 col-form-label">Adres</label>
                <div class="col-md-9">
                    <input value="{{ $customer->address }}" id="address" name="address" placeholder="Wpisz adres" type="text" aria-describedby="nameHelpBlock" class="form-control">
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
                    <input value="{{ $customer->phone }}" id="phone" name="phone" placeholder="Wpisz telefon" type="text" class="form-control">
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
                    <input value="{{ $customer->email }}" id="email" name="email" placeholder="Wpisz email" type="text" class="form-control">
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
                    <button name="submit" type="submit" class="btn btn-primary float-right">Zapisz</button>
                    <a href="{{ route('customers.list') }}" class="btn btn-success float-right">Lista klientów</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection