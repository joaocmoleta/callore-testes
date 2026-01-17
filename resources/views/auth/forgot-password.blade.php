<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <meta name="robots" content="nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }}</title>
    <style>body{margin:0;padding:0;}.loader-box{background:#333;height:100vh;position:fixed;z-index:15;width:100%;display:flex;align-items:center;justify-content:center;}.loader{width:48px;height:48px;border:5pxsolid#333;border-bottom-color:transparent;border-radius:50%;display:inline-block;box-sizing:border-box;animation:rotation1slinearinfinite;}@keyframesrotation{0%{transform:rotate(0deg);}100%{transform:rotate(360deg);}}</style>
</head>

<body>
    <div class="loader-box">
        <span class="loader"></span>
    </div>
    <header>
        <aside class="top-bar">
            <a href="{{ route('home') }}" class="logo">
                <img src="img/logo-callore.webp" alt="Logo {{ env('APP_NAME') }}">
            </a>
        </aside>
    </header>

    <section class="form-area">
        <div class="msg-register-login">
            @include('flash-message')
        </div>
        
        <form action="{{ route('password.email') }}" method="POST" class="login-form" onsubmit="sendForm(this)">
            @csrf

            <div class="input">
                <p>Esqueceu a senha? Insira seu e-mail de cadastro abaixo, que enviaremos um link de redefinição:</p>
            </div>

            <label>E-mail</label>
            <div class="input">
                <input type="text" name="email" placeholder="email@provedor.com" value="{{ old('email') }}"
                    autofocus>
                @error('email')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="forgot-password">
                <a href="{{ route('login') }}">Cancelar, lembrei minha senha</a>
            </div>

            <div class="submit">
                <button type="submit" class="bt-primary-one">Enviar link de redefinição</button>
            </div>

            <div class="alternative-login">
                <p>Não tem conta? <a href="{{ route('register') }}"><strong>Cadastre-se</strong></a></p>
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
