<section class="form-area form-area-login-register">
    <form action="{{ route('register') }}" method="POST" class="login-form">
        @csrf


        <label>Senha atual</label>
        <div class="input password">
            <svg width="20" onclick="viewPass(this)" clip-rule="evenodd" fill-rule="evenodd"
                stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="m11.998 5c-4.078 0-7.742 3.093-9.853 6.483-.096.159-.145.338-.145.517s.048.358.144.517c2.112 3.39 5.776 6.483 9.854 6.483 4.143 0 7.796-3.09 9.864-6.493.092-.156.138-.332.138-.507s-.046-.351-.138-.507c-2.068-3.403-5.721-6.493-9.864-6.493zm.002 3c2.208 0 4 1.792 4 4s-1.792 4-4 4-4-1.792-4-4 1.792-4 4-4zm0 1.5c1.38 0 2.5 1.12 2.5 2.5s-1.12 2.5-2.5 2.5-2.5-1.12-2.5-2.5 1.12-2.5 2.5-2.5z"
                    fill-rule="nonzero" />
            </svg>
            <input type="password" name="password" placeholder="Sua senha..." value="{{ old('password') }}">
            @error('password')
                <div class="msg-error">{{ $message }}</div>
            @enderror
        </div>

        <label>Nova senha</label>
        <div class="input password">
            <svg width="20" onclick="viewPass(this)" clip-rule="evenodd" fill-rule="evenodd"
                stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
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
            <svg width="20" onclick="viewPass(this)" clip-rule="evenodd" fill-rule="evenodd"
                stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
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

        <div class="submit">
            <button class="bt-primary-one">Salvar</button>
        </div>

    </form>
</section>