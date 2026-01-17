<div class="delivery">
    <p><strong>Informe seu endereço de entrega</strong></p>
    <form action="{{ route('orders.complete') }}" method="POST" style="display: flex; flex-wrap: wrap; gap: 20px">
        @csrf

        <input type="hidden" name="reference_id" value="{{ $db_order->id }}">

        <div class="field-size">
            <label>CPF</label>
            <div class="input">
                <input type="text" name="tax_id" value="{{ $complete->tax_id ?? ($user->doc ?? '') }}"
                    placeholder="22222222222">

            </div>
            <script></script>
        </div>

        <div class="field-size">
            @include('field', [
                'field_label' => 'CEP',
                'field_name' => 'postal_code',
                'field_value' => $complete->postal_code ?? '',
                'field_placeholder' => '01452002',
                'field_type' => 'text',
            ])
        </div>

        <div class="field-size-half">
            @include('field', [
                'field_label' => 'Rua',
                'field_name' => 'street',
                'field_value' => $complete->street ?? '',
                'field_placeholder' => 'Avenida Brigadeiro Faria Lima',
                'field_type' => 'text',
            ])
        </div>

        <div class="field-size">
            @include('field', [
                'field_label' => 'N⁰',
                'field_name' => 'number',
                'field_value' => $complete->number ?? '',
                'field_placeholder' => '1384',
                'field_type' => 'text',
            ])
        </div>

        <div class="field-size">
            @include('field', [
                'field_label' => 'Complemento',
                'field_name' => 'complement',
                'field_value' => $complete->complement ?? '',
                'field_placeholder' => 'ex: cj 3, casa 1, apt. 909',
                'field_type' => 'text',
            ])
        </div>

        <div class="field-size">
            @include('field', [
                'field_label' => 'Bairro',
                'field_name' => 'locality',
                'field_value' => $complete->locality ?? '',
                'field_placeholder' => 'Pinheiros',
                'field_type' => 'text',
            ])
        </div>

        <div class="field-size">
            @include('field', [
                'field_label' => 'Cidade',
                'field_name' => 'city',
                'field_value' => $complete->city ?? '',
                'field_placeholder' => 'Sao Paulo',
                'field_type' => 'text',
            ])
        </div>

        <div class="field-size">
            <label>Estado</label>
            <div class="select input">
                <input type="hidden" name="region_code"
                    value="{{ old('region_code') ?? (isset($complete->region_code) ? $complete->region_code : 'SP') }}">
                <input type="text" readonly
                    value="{{ old('region_code') ?? (isset($complete->region_code) ? $complete->region_code : 'SP') }}"
                    placeholder="Selecione" onfocus="show_opts_states(this)">
                <div class="select-options"></div>
                @error('region_code')
                    <div class="input-error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        @if (env('APP_ENV') == 'local')
            <script src="{{ asset('js/regioes_br.js') }}"></script>
            <script src="{{ asset('js/select_state.js') }}"></script>
            <script src="{{ asset('/js/select.js') }}"></script>
        @else
            <script src="{{ asset('js/regioes_br.min.js') }}"></script>
            <script src="{{ asset('js/select_state.min.js') }}"></script>
            <script src="{{ asset('/js/select.min.js') }}"></script>
        @endif


        <div class="field-size">
            <label>País</label>
            <div class="select input">
                <input type="text" readonly name="country"
                    value="{{ old('country') ? old('country') : $complete->country ?? ($user->country ?? 'BRA') }}">
                @error('country')
                    <div class="input-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="submit" style="width: 100%">
            <button class="bt-primary-danger">Continuar para o pagamento</button>
        </div>
    </form>
</div>
