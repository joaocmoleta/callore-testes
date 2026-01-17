<x-mail::message>
# Seu Pedido foi cancelado no sistema

O estorno do seu pagamento foi solicitado.

Para acompanhar todos os detalhes do seu pedido:

<x-mail::button :url="$url">
Pedido
</x-mail::button>

Obrigado,<br>
{{ config('app.name') }}
</x-mail::message>
