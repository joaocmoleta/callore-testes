<form action="{{ route('orders.storedelivery') }}" method="POST">
    @csrf
    <input type="hidden" name="order" value="{{ $order->id }}">

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
                <option value="" {{ old('method') == '' ? 'selected' : '' }}>Selecione</option>
                <option value="Transportadora" {{ old('method') == 'Transportadora' ? 'selected' : '' }}>Transportadora
                </option>
                <option value="Callore" {{ old('method') == 'Callore' ? 'selected' : '' }}>Callore</option>
                <option value="Correios" {{ old('method') == 'Correios' ? 'selected' : '' }}>Correios</option>
            </select>
        </div>
    </div>
    
    <div class="box-input box-input-15">
    <label>Status</label>
    <div class="input">
        <select name="status">
            <option value="">Selecione</option>
            <option value="shipped">{{ __('status.shipped') }}</option>
            <option value="last_stage_delivery">{{ __('status.last_stage_delivery') }}</option>
            <option value="delivered">{{ __('status.delivered') }}</option>
            <option value="rota_de_entrega">{{ __('status.rota_de_entrega') }}</option>
            <option value="delivery_updated">{{ __('status.delivery_updated') }}</option>
        </select>
    </div>
    </div>

    <div class="box-input box-input-15">
        @include('field', [
        'field_label' => 'Estimativa',
        'field_name' => 'estimative',
        'field_value' => $order->delivery_estimative
            ? \Carbon\Carbon::parse($order->delivery_estimative)->format('Y-m-d\TH:i')
            : '',
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
