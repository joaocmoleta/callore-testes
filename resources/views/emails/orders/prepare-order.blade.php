<x-mail::message>
# Seu pedido está sendo preparado

Em poucos dias você receberá seu produto, acompanhe todos os detalhes:

<x-mail::button :url="$url">
Pedido
</x-mail::button>

Obrigado,<br>
{{ config('app.name') }}
</x-mail::message>
