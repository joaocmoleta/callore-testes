<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <meta name="robots" content="nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }}</title>
<style>body{margin:0;padding:0;}.loader-box{background:#333;height:100vh;position:fixed;top:0;left:0;right:0;z-index:15;width:100%;display:flex;align-items:center;justify-content:center;}.loader{width:48px;height:48px;border:5pxsolid#333;border-bottom-color:transparent;border-radius:50%;display:inline-block;box-sizing:border-box;animation:rotation1slinearinfinite;}@keyframesrotation{0%{transform:rotate(0deg);}100%{transform:rotate(360deg);}}</style>
</head>

<body>
    <div class="loader-box">
        <span class="loader"></span>
    </div>
    <header class="header-login-register">
        <aside class="top-bar">
            <a href="{{ route('home') }}" class="logo">
                <img src="img/logo-callore.webp" alt="Logo {{ env('APP_NAME') }}">
            </a>
        </aside>

    </header>

    <section class="form-area form-area-login-register">
        <form action="{{ route('register') }}" method="POST" class="login-form">
            @csrf

            @include('field', [
                'field_name' => 'name',
                'field_value' => old('name'),
                'field_label' => 'Nome completo',
                'field_type' => 'text',
                'field_placeholder' => 'José da Silva',
            ])

            @include('field', [
                'field_name' => 'doc',
                'field_value' => old('doc'),
                'field_label' => 'Documento (CPF/CNPJ)',
                'field_type' => 'text',
                'field_placeholder' => '00000000000',
            ])

            @include('field', [
                'field_name' => 'email',
                'field_value' => old('email'),
                'field_label' => 'E-mail',
                'field_type' => 'text',
                'field_placeholder' => 'email@provedor.com.br',
            ])

            <label>Data nascimento</label>
            <div class="input">
                <input type="date" name="birth" value="{{ old('birth') }}">
            </div>

            <label>País DDI</label>
            <div class="input">
                <select id="countries" name="ddi">
                    <option value="{{ old('ddi') ?? '55' }}">{{ old('ddi') ? '+' . old('ddi') : '+55 Brasil' }}</option>
                </select>
            </div>

            <label>Telefone DDD + número</label>
            <div class="input-phone-ddi-ddd-n">
                (<input type="text" name="ddd" value="{{ old('ddd') }}" placeholder="ddd" maxlength="2"
                    class="ddd">)
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="988889999"
                    onkeyup="this.value = mtels(this.value)" maxlength="10" class="phone">
            </div>
            @error('ddi')
                <div class="msg-error">{{ $message }}</div>
            @enderror

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

            <label>Senha</label>
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

            <div class="forgot-password">
                <p>Ao se cadastrar você concorda com nossas <a href="{{ route('privacity-polices') }}"
                        target="_blank">políticas de privacidade</a></p>
            </div>

            <div class="submit">
                <button class="bt-primary-one">Cadastrar</button>
            </div>

            <div class="alternative-login">
                <p>Já tem conta? <a href="{{ route('login') }}"><strong>Faça o login</strong></a></p>
            </div>

        </form>
    </section>

    <footer>
        <aside class="copy-right">
            <p>Desenvolvido por <a href="https://moleta.com.br" target="_blank">Mol Tecnologia e Inovação</a>
                &copy; Todos os direitos reservados <strong>{{ env('APP_NAME') }}</strong>
                {{ \Carbon\Carbon::now()->year }}</p>
        </aside>
    </footer>

    <script src="{{ asset('js/password.min.js') }}"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/reset-css@5.0.1/reset.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.min.css?v=4.4') }}">
</body>

</html>
