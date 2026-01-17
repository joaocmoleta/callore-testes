<x-mail::message>

Sua solicitação de coleta foi recebida pela Total Express em {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }} às {{ \Carbon\Carbon::parse($hour)->format('H:i') }}.

Prepare o seu produto e aguarde a retirada.

@component('mail::subcopy')
## Importante:
ℹ️ Horário de corte às 10h59;

ℹ️ Horário de coleta: de segunda a sexta das 12h às 18h - dias úteis;

ℹ️ Até 15 minutos motorista da coleta aguarda.

⚠ Confira os dados de entrega, quantidade de volumes e demais dados na etiqueta e romaneio.
@endcomponent

@component('mail::subcopy')
## Na embalagem do produto:

☑️ Imprimir e afixar a Danfe com código de barras em local evidente;

☑️ Imprimir e afixar a etiqueta (em anexo);

☑️ Limites de 30Kg, 120x80x60cm.
@endcomponent

@component('mail::subcopy')
## Em mãos:

☑️ Estar com romaneio (em anexo) impresso para motorista da coleta assinar e preencher.
@endcomponent

@component('mail::button', ['url' => route('orders.edit', $order_id)])
    Ver detalhes do pedido
@endcomponent

</x-mail::message>
