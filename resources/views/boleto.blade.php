{{-- // Boleto --}}
<div class="option-box">
    <h4 onclick="selectPay(this)"><svg clip-rule="evenodd" width="15" fill-rule="evenodd" stroke="black"
            fill="{{ session()->get('pay') == 'boleto' ? 'rgb(51, 51, 51)' : 'none' }}" stroke-linejoin="round"
            stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <circle cx="11.998" cy="11.998" fill-rule="nonzero" r="9.998" />
        </svg> Boleto Bancário</h4>
    <div class="option" style="display: {{ session()->get('pay') == 'boleto' ? 'flex;' : 'none;' }}">
        <form action="{{ route('pay.boleto') }}" method="POST">
            @csrf

            <input type="hidden" name="reference_id" value="{{ $db_order->id }}">

            @include('field', [
                'field_label' => 'Nome completo',
                'field_name' => 'card_holder_name',
                'field_value' => $complete->cd_holder_name ?? ($user->name ?? ''),
                'field_placeholder' => 'Jose da Silva',
                'field_type' => 'text',
            ])

            @include('field', [
                'field_label' => 'CPF',
                'field_name' => 'tax_id',
                'field_value' => $complete->tax_id ?? ($user->doc ?? ''),
                'field_placeholder' => '22222222222',
                'field_type' => 'number',
            ])

            @include('field', [
                'field_label' => 'E-mail',
                'field_name' => 'email',
                'field_value' => $complete->email ?? ($user->email ?? ''),
                'field_placeholder' => 'jose@email.com',
                'field_type' => 'text',
            ])

            @include('field', [
                'field_label' => 'CEP',
                'field_name' => 'postal_code',
                'field_value' => $complete->postal_code ?? ($user->postal_code ?? ''),
                'field_placeholder' => '01452002',
                'field_type' => 'number',
            ])

            @include('field', [
                'field_label' => 'Rua',
                'field_name' => 'street',
                'field_value' => $complete->street ?? ($user->street ?? ''),
                'field_placeholder' => 'Avenida Brigadeiro Faria Lima',
                'field_type' => 'text',
            ])

            @include('field', [
                'field_label' => 'N⁰',
                'field_name' => 'number',
                'field_value' => $complete->number ?? ($user->number ?? ''),
                'field_placeholder' => '1384',
                'field_type' => 'text',
            ])

            @include('field', [
                'field_label' => 'Complemento',
                'field_name' => 'complement',
                'field_value' => $complete->complement ?? ($user->complement ?? ''),
                'field_placeholder' => 'ex: cj 3, casa 1, apt. 909',
                'field_type' => 'text',
            ])

            @include('field', [
                'field_label' => 'Bairro',
                'field_name' => 'locality',
                'field_value' => $complete->locality ?? ($user->locality ?? ''),
                'field_placeholder' => 'Pinheiros',
                'field_type' => 'text',
            ])

            @include('field', [
                'field_label' => 'Cidade',
                'field_name' => 'city',
                'field_value' => $complete->city ?? ($user->city ?? ''),
                'field_placeholder' => 'Sao Paulo',
                'field_type' => 'text',
            ])

            <div class="field-size">
                <label>Estado</label>
                <div class="select input">
                    <input type="hidden" name="region_code"
                        value="{{ old('region_code') ? old('region_code') : $complete->region_code ?? ($user->region_code ?? 'SP') }}">
                    <input type="text" readonly
                        value="{{ old('region_code') ? old('region_code') : $complete->region_code ?? ($user->region_code ?? 'SP') }}"
                        placeholder="Selecione" onfocus="show_opts_states(this)">
                    <div class="select-options"></div>
                    @error('region_code')
                        <div class="input-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- <script>
                function show_opts_states(e) {
                    show_opts(e, states_br_codes)
                }
            </script> --}}

            <div class="field-size">
                <label>País</label>
                <div class="select input">
                    <input type="hidden" name="country"
                        value="{{ old('country') ? old('country') : $complete->country ?? ($user->country ?? 'SP') }}">
                    <input type="text" readonly
                        value="{{ old('country') ? old('country') : $complete->country ?? ($user->country ?? 'SP') }}"
                        placeholder="Selecione" onfocus="show_opts_countries(this)">
                    <div class="select-options"></div>
                    @error('country')
                        <div class="input-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <script>
                function show_opts_countries(e) {
                    show_opts(e, countries_codes)
                }
            </script>

            <div class="submit">
                <button class="bt-primary-danger">Gerar boleto</button>
            </div>
        </form>
    </div>
</div>
