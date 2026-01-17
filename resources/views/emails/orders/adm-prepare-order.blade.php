<x-mail::message>
# Um novo pedido esperando o preparo e entrega

# Segue os dados do comprador:


**Nome**: {{ $name }}<br>
**CPF**: {{ $tax_id }}<br>
**E-mail**: {{ $email }}<br>
**Telefone**: {{ $phone }}<br>
@if ($coupon)
**Cupom utilizado**: {{ $coupon ?? 'Nenhum' }}<br>
@endif
**Endereço**:
{{ $street }} - {{ $number }} - {{ $locality }} - {{ $city }}/{{ $region_code }} - {{ $country }} - CEP: {{ $postal_code }}

# Produtos
{{-- @foreach (json_decode($products) as $item)
    {{ preg_replace("/\|/", '', $item->name) }}
@endforeach --}}

@component('mail::table')
    | Produto | Qtd | Unitário | Subtotal | 
    | --------------------------------- |:-------------:| --------: | --------: |
    @foreach ($products as $item)
    | {{ preg_replace("/\|/", '', $item->name) }} | {{ $item->qtd }} | {{ number_format($item->value_uni, 2, ',', '.') }} | {{ number_format($item->subtotal, 2, ',', '.') }}
    @endforeach
    @if ($discounts)
    |  |  | Descontos | {{ number_format($discounts, 2, ',', '.') }}
    @endif
    |  |  | Total | {{ number_format($amount, 2, ',', '.') }}
@endcomponent

Parabéns por mais uma venda.

{{ config('app.name') }}
</x-mail::message>
