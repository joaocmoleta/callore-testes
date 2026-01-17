<x-mail::message>
# Segue o boleto para pagamento do pedido n⁰ {{ $id }}

Código de barras: {{ $barcode }}

Para visualizar ou baixar o boleto segue o link abaixo:

<x-mail::button :url="$url">
Boleto em PDF
</x-mail::button>

Para acompanhar todos os detalhes do seu pedido:

<x-mail::button :url="$url">
Pedido
</x-mail::button>

Obrigado,<br>
{{ config('app.name') }}
</x-mail::message>
