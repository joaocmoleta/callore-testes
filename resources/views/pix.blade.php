<div class="option-box">
    <h4 onclick="selectPay(this)"><svg clip-rule="evenodd" width="15" fill-rule="evenodd" stroke="black"
            fill="{{ session()->get('pay') == 'pix' ? 'rgb(51, 51, 51)' : 'none' }}" stroke-linejoin="round"
            stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <circle cx="11.998" cy="11.998" fill-rule="nonzero" r="9.998" />
        </svg> PIX</h4>
    <div class="option" style="display: {{ session()->get('pay') == 'pix' ? 'flex;' : 'none;' }}">
        <form action="{{ route('pay.pix') }}" method="POST">
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
                'field_label' => 'E-mail',
                'field_name' => 'email',
                'field_value' => $user->email ?? '',
                'field_placeholder' => 'Jose da Silva',
                'field_type' => 'text',
            ])

            @include('field', [
                'field_label' => 'CPF',
                'field_name' => 'tax_id',
                'field_value' => $complete->tax_id ?? ($user->doc ?? ''),
                'field_placeholder' => '22222222222',
                'field_type' => 'text',
            ])

            <label>Telefone</label>
            {{-- <div class="input select">
                <input type="text" onfocus="show_opts_ddi(this)">
                <div class="select-options"></div>
            </div> --}}

            {{-- <script>
                function show_opts_ddi(e) {
                    e.nextElementSibling.style.display = 'initial'
                    countries_set = Object.keys(countries)

                    countries_set.forEach(country => {
                        console.log(countries[country].pais, countries[country].ddi)

                        let opt = document.createElement('div')
                        opt.classList.add('sel-option')
                        opt.setAttribute('onclick', `selected_country_ddi(this)`)
                        opt.value = countries[country].ddi
                        opt.innerText = countries[country].pais
                        e.nextElementSibling.append(opt)
                    })
                    // console.log(countries)
                    // e.nextElementSibling.style.display = 'initial'

                    // arr.forEach(element => {
                    //     let opt = document.createElement('div')
                    //     opt.classList.add('sel-option')
                    //     opt.setAttribute('onclick', `selected_country_ddi(this)`)
                    //     opt.value = element.code
                    //     opt.innerText = element.name
                    //     e.nextElementSibling.append(opt)
                    // });
                }

                function selected_country_ddi(e) {
                    e.parentElement.style.display = 'none'
                    e.parentElement.previousElementSibling.value = e.value
                    // e.parentElement.previousElementSibling.previousElementSibling.value = e.value
                }
            </script> --}}

            <div class="input">
                <select id="countries" name="ddi" onmousedown="loadCountries(this)">
                    <option value="{{ old('ddi') ?? ($user->phone ? substr($user->phone, 0, 2) : '55') }}">
                        {{ old('ddi') ?? ($user->phone ? '+' . substr($user->phone, 0, 2) : '+55 Brasil') }}</option>
                </select>
            </div>
            @error('ddi')
                <div class="input-error">{{ $message }}</div>
            @enderror

            <div class="input-phone-ddi-ddd-n" style="margin-bottom: 20px">
                (<input type="text" name="ddd" value="{{ old('ddd') ?? substr($user->phone, 2, 2) }}"
                    placeholder="ddd" maxlength="2" class="ddd">)
                <input type="text" name="phone" value="{{ old('phone') ?? substr($user->phone, 4) }}"
                    placeholder="988889999" onkeyup="this.value = mtels(this.value)" maxlength="10" class="phone">
            </div>
            @error('ddd')
                <div class="input-error">{{ $message }}</div>
            @enderror
            @error('phone')
                <div class="input-error">{{ $message }}</div>
            @enderror

            @include('field', [
                'field_label' => 'CEP',
                'field_name' => 'postal_code',
                'field_value' => $complete->postal_code ?? ($user->postal_code ?? ''),
                'field_placeholder' => '01452002',
                'field_type' => 'text',
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
                <script src="{{ asset('js/states_br_codes.js') }}"></script>
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
                    <input readonly type="text" name="country"
                        value="{{ old('country') ? old('country') : $complete->country ?? ($user->country ?? 'BRA') }}">
                    @error('country')
                        <div class="input-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="submit">
                <button class="bt-primary-danger">Gerar QR Code</button>
            </div>
        </form>
    </div>
</div>
