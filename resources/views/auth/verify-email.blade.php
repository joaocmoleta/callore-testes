<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
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
            <a href="{{ route('home') }}">
                <img src="img/logo-callore.webp" alt="Logo {{ env('APP_NAME') }}">
            </a>
        </aside>

    </header>

    <section class="form-area">
        <div class="login-form">
            <h3>Verifique seu e-mail</h3>
            <br>
            <p>Enviamos um e-mail para seu endereço com o link de confirmação. Para manter a segurança e os termos da LGPD, necessitamos a continuação do processo. Caso não tenha recebido tente:</p>
            <br>
            <form action="{{ route('verification.send') }}" method="POST">
                @csrf
    
                <div class="submit">
                    <button class="bt-primary-one">Reenviar e-mail de verificação</button>
                </div>
            </form>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
    
                <div class="submit">
                    <button type="submit" class="bt-secondary-one">Sair da conta</button>
                </div>
            </form>
        </div>
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