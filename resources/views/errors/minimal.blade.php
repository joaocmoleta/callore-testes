<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
</head>

<body>
    <div class="error-404">
        <div class="content-404">
            <img src="/img/404.png" alt="404">
            <h2>@yield('title')</h2>
            <h3>Erro @yield('code')</h3>
            <p>@yield('message')</p>
            <p>
                <a href="{{ route('home') }}" class="bt-primary-one">Ir para a home</a>
            </p>
        </div>
    </div>
    @if (env('APP_ENV') == 'production')
        <link rel="stylesheet" href="{{ asset('/css/styles.min.css?v=4.4') }}">
    @else
        <link rel="stylesheet" href="{{ asset('/css/styles.css?v=4.4') }}">
    @endif
</body>

</html>
