<section class="form-area form-area-login-register">

    <div class="msg-register-login">
        @include('flash-message')
    </div>

    <form action="{{ route('profile.update') }}" method="POST" class="login-form">
        @csrf
        @method('patch')

        <div class="input">
            <h3>Atualizar informações da conta</h3>
        </div>

        @include('field', [
            'field_name' => 'name',
            'field_value' => old('name') ?? $user->name,
            'field_label' => 'Nome completo',
            'field_type' => 'text',
            'field_placeholder' => 'José da Silva',
        ])

        @include('field', [
            'field_name' => 'doc',
            'field_value' => old('doc') ?? $user->doc,
            'field_label' => 'Documento (CPF/CNPJ)',
            'field_type' => 'text',
            'field_placeholder' => '00000000000',
        ])

        @include('field', [
            'field_name' => 'email',
            'field_value' => old('email') ?? $user->email,
            'field_label' => 'E-mail',
            'field_type' => 'text',
            'field_placeholder' => 'email@provedor.com.br',
        ])

        <label>País DDI</label>
        <div class="input">
            <select id="countries" name="ddi">
                <option value="">Selecione</option>
                <option selected value="{{ old('ddi') ?? '55' }}">{{ old('ddi') ? '+' . old('ddi') : '+55 Brasil' }}
                </option>
            </select>
        </div>
        @error('ddi')
            <div class="msg-error">{{ $message }}</div>
        @enderror

        <label>Telefone DDD + número</label>
        <div class="input-phone-ddi-ddd-n">
            (<input type="text" name="ddd" value="{{ old('ddd') ?? substr($user->phone, 2, 2) }}"
                placeholder="" maxlength="2" class="ddd">)
            <input type="text" name="phone" value="{{ old('phone') ?? substr($user->phone, 4) }}"
                placeholder="988889999" onkeyup="this.value = mtels(this.value)" maxlength="10" class="phone">
        </div>

        <script src="js/countries.min.js"></script>
        <script>
            let html_countries = document.getElementById("countries")

            for (const [key, value] of Object.entries(countries)) {
                let option = document.createElement('option')
                option.textContent = `+${value['ddi']} ${value['pais']}`
                option.setAttribute('value', '')
                html_countries.append(option)
            }
        </script>

        @error('ddd')
            <div class="msg-error">{{ $message }}</div>
        @enderror
        @error('phone')
            <div class="msg-error">{{ $message }}</div>
        @enderror

        <label>Trocar de senha</label>
        <div class="input">
            <input type="checkbox" name="change_pass" onclick="changePass()" {{ old('change_pass') ? 'checked' : '' }}>
        </div>

        <div class="change-pass" id="change_pass" style="{{ old('change_pass') ? 'display: initial' : 'display: none' }}">
            <label>Senha atual</label>
            <div class="input password">
                <svg width="20" onclick="viewPass(this)" clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round"
                    stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="m11.998 5c-4.078 0-7.742 3.093-9.853 6.483-.096.159-.145.338-.145.517s.048.358.144.517c2.112 3.39 5.776 6.483 9.854 6.483 4.143 0 7.796-3.09 9.864-6.493.092-.156.138-.332.138-.507s-.046-.351-.138-.507c-2.068-3.403-5.721-6.493-9.864-6.493zm.002 3c2.208 0 4 1.792 4 4s-1.792 4-4 4-4-1.792-4-4 1.792-4 4-4zm0 1.5c1.38 0 2.5 1.12 2.5 2.5s-1.12 2.5-2.5 2.5-2.5-1.12-2.5-2.5 1.12-2.5 2.5-2.5z"
                        fill-rule="nonzero" />
                </svg>
                <input type="password" name="current_pass" placeholder="Sua senha..." value="{{ old('current_pass') }}">
                @error('current_pass')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>
    
            <label>Nova senha</label>
            <div class="input password">
                <svg width="20" onclick="viewPass(this)" clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round"
                    stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="m11.998 5c-4.078 0-7.742 3.093-9.853 6.483-.096.159-.145.338-.145.517s.048.358.144.517c2.112 3.39 5.776 6.483 9.854 6.483 4.143 0 7.796-3.09 9.864-6.493.092-.156.138-.332.138-.507s-.046-.351-.138-.507c-2.068-3.403-5.721-6.493-9.864-6.493zm.002 3c2.208 0 4 1.792 4 4s-1.792 4-4 4-4-1.792-4-4 1.792-4 4-4zm0 1.5c1.38 0 2.5 1.12 2.5 2.5s-1.12 2.5-2.5 2.5-2.5-1.12-2.5-2.5 1.12-2.5 2.5-2.5z"
                        fill-rule="nonzero" />
                </svg>
                <input type="password" name="password" placeholder="Sua senha..." value="{{ old('password') }}">
                @error('password')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>
    
            <label>Confirmação de senha</label>
            <div class="input password">
                <svg width="20" onclick="viewPass(this)" clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round"
                    stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="m11.998 5c-4.078 0-7.742 3.093-9.853 6.483-.096.159-.145.338-.145.517s.048.358.144.517c2.112 3.39 5.776 6.483 9.854 6.483 4.143 0 7.796-3.09 9.864-6.493.092-.156.138-.332.138-.507s-.046-.351-.138-.507c-2.068-3.403-5.721-6.493-9.864-6.493zm.002 3c2.208 0 4 1.792 4 4s-1.792 4-4 4-4-1.792-4-4 1.792-4 4-4zm0 1.5c1.38 0 2.5 1.12 2.5 2.5s-1.12 2.5-2.5 2.5-2.5-1.12-2.5-2.5 1.12-2.5 2.5-2.5z"
                        fill-rule="nonzero" />
                </svg>
                <input type="password" name="password_confirmation" placeholder="Sua senha..."
                    value="{{ old('password_confirmation') }}">
                @error('password_confirmation')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <script>
            function changePass() {
                let change_pass = document.getElementById('change_pass')
                console.log(change_pass);
                if(change_pass.style.display == 'initial') {
                    change_pass.style.display = 'none'
                } else {
                    change_pass.style.display = 'initial'
                }
            }
        </script>

        <div class="submit">
            <button class="bt-primary-one">Atualizar</button>
        </div>

    </form>
</section>
