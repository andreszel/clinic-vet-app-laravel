@component('mail::message')
# {{ $details['title'] }}

<div class="contener-message">
    <p class="text-center mb-3" style="text-align:center;margin:35px 0;">
        <img class="img-fluid rounded mx-auto" src="{{ asset('img/trzaskacz-vet-weterynarz-piotrkow-trybunalski-logo.png') }}" alt="Logo" />
    </p>
    <p class="mb-3">W aplikacji {{ config('app.name') }} zostało założone konto.</p>
    <p>Twoje tymczasowe hasło do logowania: <strong>{{ $details['random_password'] }}</strong></p>
    <p>Po zalogowaniu hasło musisz zmienić na nowe!</p>
    <p>Kliknij w link poniżej, aby zalogować się na swoje konto.</p>
    @component('mail::button', ['url' => $details['url'], 'color' => 'success'])
    LOGOWANIE
    @endcomponent
</div>

Dziękujemy,<br>
{{ config('app.name') }}
@endcomponent