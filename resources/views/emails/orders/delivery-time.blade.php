<x-mail::message>
# Estimativa de entrega do seu pedido foi atualizada

Entrega prevista para dia **{{ \Carbon\Carbon::parse($estimative)->format('d/m/Y') }}**

Para acompanhar todos os detalhes do seu pedido:

<x-mail::button :url="$url">
Pedido
</x-mail::button>

Obrigado,<br>
{{ config('app.name') }}
</x-mail::message>
