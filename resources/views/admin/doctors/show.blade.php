show user !
<br />
{{ dump($user) }}
<br />
Właściciel zalogowanego konta: {{ $profile_logged ? 'tak' : 'nie' }}
<br />
Pomysł jest taki, zalogowanego user, jeżeli próbuje podejrzeć swoje konto wyświetlił się komunikat, że To jest Twoje konto lub, żeby user został przekierowany do edycji swoich danych, profilu użytkownika.
<br />