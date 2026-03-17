

<x-mail::message>
# Willkommen bei uns, {{ $user->name }}!

Ihr Kundenkonto wurde erfolgreich generiert und Ihre Anfrage wurde bestätigt. 

Sie können sich nun in unser Kundenportal einloggen, um den Status Ihrer Anfrage zu verfolgen.

<p><strong>Ihre Zugangsdaten:</strong></p>
<ul style="list-style: none; padding-left: 0;">
    <li><strong>Benutzername:</strong> {{ $user->username }}</li>
    <li><strong>E-Mail:</strong> {{ $user->email }}</li>
    <li><strong>Temporäres Passwort:</strong> <code style="background: #f4f4f4; padding: 2px 5px;">{{ $password }}</code></li>
</ul>
<p style="font-size: 0.9em; color: #666;">
    * Sie können sich wahlweise mit Ihrem Benutzernamen oder Ihrer E-Mail-Adresse anmelden.
</p>

<x-mail::button :url="url('/login')">
Zum Kundenportal einloggen
</x-mail::button>

Aus Sicherheitsgründen empfehlen wir, das Passwort nach dem ersten Login zu ändern, oder verwenden Sie die Funktion "Passwort vergessen" auf der Login-Seite, um ein neues festzulegen.

Mit freundlichen Grüßen,<br>
Ihr Team von Agentur-77
</x-mail::message>
