@extends('layouts.admin')

@section('content')

<div class="row">
    <div class="col-md-6">
        <h1 class="h3 mb-2 text-gray-800">Moje konto</h1>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user"></i> Profil konta</h6>
            </div>
            <div class="card-body">
                <p>Dane Twojego konta w aplikacji: {{ config('app.name') }}</p>
                <div class="py-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Twoje konto</h4>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12" title="Imię i nazwisko">
                            {{ $user->name }} {{ $user->surname }}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12" title="Adres email">
                            <i class="fas fa-at"></i> {{ $user->email }}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12" title="Numer telefonu">
                            <i class="fas fa-phone"></i> {{ $user->phone }}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12" title="Data utworzenia konta">
                            <i class="fas fa-calendar"></i> {{ $user->created_at }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <h1 class="h3 mb-2 text-gray-800">Zmiana hasła</h1>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Formularz</h6>
            </div>
            <div class="card-body">

                <form action="{{ route('me.settings.update') }}" method="POST">
                    @csrf

                    <div class="form-group row">
                        <label for="old_password" class="col-md-3 col-form-label">{{ __('Stare hasło') }}</label>

                        <div class="col-md-9">
                            <input id="old_password" type="password" class="form-control @error('old_password') is-invalid @enderror" name="old_password" autofocus required autocomplete="olc-password">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-3 col-form-label">{{ __('Nowe hasło') }}</label>

                        <div class="col-md-9">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                            <small>Hasło musi składać się z z min. 8 znaków, 1 dużej litery, 1 małej litery, 1 cyfry i 1 znaku specjalnego.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm" class="col-md-3 col-form-label">{{ __('Potwierdź nowe hasło') }}</label>

                        <div class="col-md-9">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Zmień hasło') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection