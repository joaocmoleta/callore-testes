<div class="nota-fiscal">
            <h3>Nota fiscal</h3>
    <p><strong>Adicionada:</strong> {{ \Carbon\Carbon::parse($order->adicionada)->format('d/m/Y H:i:s') }}</p>
    <p><strong>Número:</strong> {{ $order->numero }}</p>
    <p><strong>Série</strong> {{ $order->serie }}</p>
    <p><strong>Data de emissão:</strong> {{ \Carbon\Carbon::parse($order->data_emissao)->format('d/m/Y') }}</p>
    <p><strong>Total nota</strong> R$ {{ number_format($order->total, 2, ',', '.') }}</p>
    <p><strong>Total produtos</strong> R$ {{ number_format($order->produto, 2, ',', '.') }}</p>
    <p><strong>Chave</strong> {{ $order->chave }}</p>
    <br>
</div>

<div class="envio-total-express">
    <h3>Envio Total Express</h3>
    @if ($order->pedido)
        @include('dashboard.orders.coleta-ok')
    @else
        @include('dashboard.orders.coleta-pendente')
    @endif
</div>
