<form action="{{ route('orders.updelivery') }}" method="POST">
    @csrf
    <input type="hidden" name="order" value="{{ $order->id }}">
    <input type="hidden" name="id" value="{{ $order->delivery_id }}">

    <p><strong>Status atual:</strong> {{ $order->delivery_status }}</p>

    <div class="box-input box-input-15">
        @include('field', [
            'field_label' => 'Rastreio',
            'field_name' => 'code',
            'field_value' => old('code') ?? $order->delivery_code,
            'field_placeholder' => '27D7D4A97DA4',
            'field_type' => 'text',
        ])
    </div>

    <div class="box-input box-input-15">
        <label>Método</label>
    <div class="input">
        <select name="method">
            <option value="" {{ $order->delivery_method == '' ? 'selected' : '' }}>Selecione</option>
            <option value="Transportadora" {{ $order->delivery_method == 'Transportadora' ? 'selected' : '' }}>
                Transportadora</option>
            <option value="Callore" {{ $order->delivery_method == 'Callore' ? 'selected' : '' }}>Callore</option>
            <option value="Correios" {{ $order->delivery_method == 'Correios' ? 'selected' : '' }}>Correios</option>
        </select>
    </div>
</div>

    <div class="box-input box-input-15">
        <label>Status</label>
    <div class="input">
        <select name="status">
            <option value="" selected>{{ __('status.' . $order->delivery_status) }}</option>
            <option value="" {{ old('status') || $order->delivery_status == '' ? 'selected' : '' }}>Selecione
            </option>
            <option value="shipped" {{ old('status') || $order->delivery_status == 'shipped' ? 'selected' : '' }}>
                {{ __('status.shipped') }}</option>
            <option value="last_stage_delivery"
                {{ old('status') || $order->delivery_status == 'last_stage_delivery' ? 'selected' : '' }}>
                {{ __('status.last_stage_delivery') }}</option>
            <option value="delivered" {{ old('status') || $order->delivery_status == 'delivered' ? 'selected' : '' }}>
                {{ __('status.delivered') }}</option>
            <option value="rota_de_entrega"
                {{ old('status') || $order->delivery_status == 'rota_de_entrega' ? 'selected' : '' }}>
                {{ __('status.rota_de_entrega') }}</option>
            <option value="delivery_updated"
                {{ old('status') || $order->delivery_status == 'delivery_updated' ? 'selected' : '' }}>
                {{ __('status.delivery_updated') }}</option>
        </select>
    </div>
    </div>

    <div class="box-input box-input-15">
        @include('field', [
        'field_label' => 'Estimativa',
        'field_name' => 'estimative',
        'field_value' => $order->delivery_estimative ? \Carbon\Carbon::parse($order->delivery_estimative)->format('Y-m-d\TH:i') : '',
        'field_placeholder' => 'Despachado',
        'field_type' => 'datetime-local',
    ])
    </div>

    <label>Notificar cliente da mudança?</label>
    <div class="input">
        <input type="checkbox" name="notify" checked>
    </div>

    <div class="submit">
        <button class="bt-primary-one">Atualizar dados de entrega</button>
    </div>
</form>
