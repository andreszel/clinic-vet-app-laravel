@extends('layouts.admin')

@section('content')

<div class="row">
    <div class="col-md-6">
        <h1 class="h3 mb-2 text-gray-800">Profil użytkownika</h1>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user"></i> {{ $user->name }} {{ $user->surname }} @if($profile_logged) <span class="text-danger">TWOJE KONTO</span> @endif</h6>
            </div>
            <div class="card-body">
                <div class="row my-3">
                    <div class="col-md-12" title="Adres email">
                        <i class="fas fa-at"></i> {{ $user->email }}
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col-md-12" title="Numer telefonu">
                        <i class="fas fa-phone"></i> {{ $user->phone }}
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col-md-12" title="Data utworzenia konta">
                        <i class="fas fa-calendar"></i> {{ $user->created_at }}
                    </div>
                </div>
                @if($user->type_id == 2)
                <div class="row my-3">
                    <div class="col-md-12" title="Liczba wizyt lekarskich">
                        <i class="fas fa-fw fa-briefcase-medical"></i> Wizyty lekarskie: 5
                    </div>
                </div>
                @endif
                <div class="row my-3">
                    <div class="col-md-12" title="Powrót do listy użytkowników">
                        <a href="{{ url()->previous() }}" class="btn btn-success float-right">Powrót</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection