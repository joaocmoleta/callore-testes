<x-mail::message>
# Seu pedido encontra-se como: {{ __('status.' . $status) }}

Para acompanhar todos os detalhes do seu pedido:

<x-mail::button :url="$url">
Pedido
</x-mail::button>

Obrigado,<br>
{{ config('app.name') }}
</x-mail::message>
