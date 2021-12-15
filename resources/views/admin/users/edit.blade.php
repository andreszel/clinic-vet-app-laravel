@extends('layouts.admin')

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Edycja konta użytkownika</h1>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Formularz</h6>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('users.update', ['id'=>$user->id]) }}">
            @csrf
            @method('PUT')
            <div class="form-group row">
                <label for="name" class="col-md-3 col-form-label">Imię</label>
                <div class="col-md-9">
                    <input value="{{ $user->name }}" id="name" name="name" placeholder="Wpisz imię" type="text" aria-describedby="nameHelpBlock" class="form-control" required="required">
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
                    <input value="{{ $user->surname }}" id="surname" name="surname" placeholder="Wpisz nazwisko" type="text" aria-describedby="nameHelpBlock" class="form-control" required="required">
                    @error('surname')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="email" class="col-md-3 col-form-label">E-mail</label>
                <div class="col-md-9">
                    <input value="{{ $user->email }}" id="email" name="email" placeholder="Wpisz email" type="text" class="form-control" required="required">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="phone" class="col-md-3 col-form-label">Telefon</label>
                <div class="col-md-9">
                    <input value="{{ $user->phone }}" id="phone" name="phone" placeholder="Wpisz telefon" type="text" class="form-control" required="required">
                    @error('phone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="commission_services" class="col-md-3 col-form-label">Prowizja usługi</label>
                <div class="col-md-9">
                    <div class="input-group">
                        <input value="{{ $user->commission_services }}" id="commission_services" name="commission_services" placeholder="Wpisz wysokość prowizji" type="number" class="form-control" required="required">
                        <div class="input-group-append">
                            <div class="input-group-text">%</div>
                        </div>
                    </div>
                    @error('commission_services')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label for="commission_medicals" class="col-md-3 col-form-label">Prowizja leki</label>
                <div class="col-md-9">
                    <div class="input-group">
                        <input value="{{ $user->commission_medicals }}" id="commission_medicals" name="commission_medicals" placeholder="Wpisz wysokość prowizji" type="number" class="form-control" required="required">
                        <div class="input-group-append">
                            <div class="input-group-text">%</div>
                        </div>
                    </div>
                    @error('commission_medicals')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label">Konto aktywne</label>
                <div class="col-md-9">
                    <div class="custom-controls-stacked">
                        <div class="custom-control custom-radio">
                            <input name="active" id="active_0" type="radio" required="required" class="custom-control-input" value="1" {{ $user->active ? 'checked="checked"' : '' }}>
                            <label for="active_0" class="custom-control-label">Tak</label>
                        </div>
                    </div>
                    <div class="custom-controls-stacked">
                        <div class="custom-control custom-radio">
                            <input name="active" id="active_1" type="radio" required="required" class="custom-control-input" value="0" {{ $user->active == false ? 'checked="checked"' : '' }}>
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
                <label class="col-md-3">Typ użytkownika</label>
                <div class="col-md-9">
                    <select id="type_id" name="type_id" required="required" class="custom-select">
                        @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ $user->type_id == $type->id ? 'selected="selected"' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
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