<x-mail::message>
# Recebemos o pagamento do seu pedido

Seu pedido está sendo preparado e será despachado em breve.

Para acompanhar todos os detalhes do seu pedido:

<x-mail::button :url="$url">
Pedido
</x-mail::button>

Obrigado,<br>
{{ config('app.name') }}
</x-mail::message>
