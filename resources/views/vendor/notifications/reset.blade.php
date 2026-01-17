<x-mail::message>

Você solicitou a redefinição de senha através do formulário do site.

Utilize o link abaixo:

<x-mail::button :url="$actionUrl">
    {{ $actionText }}
</x-mail::button>

Caso não tenha realizado esta solicitação, informe o administrador do site e não informe a ninguém o link acima, nem o código de redefinição.

Obrigado,<br>
{{ config('app.name') }}

<x-slot:subcopy>
    Se você tiver algum problema ao clicar no botão, copie e cole a URL a seguir no navegador para confirmar e concordar: {{ $actionUrl }}
</x-slot:subcopy>
</x-mail::message>
