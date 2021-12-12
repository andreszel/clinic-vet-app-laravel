@component('mail::message')
# {{ $details['title'] }}

<div class="contener-message">
    <p class="mb-3">W aplikacji {{ config('app.name') }} zostało założone konto.</p>
    <p>Twoje tymczasowe hasło do logowania: <strong>{{ $details['random_password'] }}</strong>!</p>
    <p>Musisz je zmienić na nowe!</p>
    @component('mail::button', ['url' => $details['url'], 'color' => 'success'])
    Ustaw nowe hasło
    @endcomponent
</div>

Dziękujemy,<br>
{{ config('app.name') }}
@endcomponent