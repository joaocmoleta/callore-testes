<!DOCTYPE html>
<html lang="pt-br">

<head>
    @if (env('APP_ENV') == 'production')
        @include('templates.tags-rastreamento')
    @endif

    @php
        $og_title = 'Catálogo Aquecedor de Toalhas Callore';
        $og_image = '/img/catalogo-aquecedor-capa.jpg';
        $description = 'Conheça um pouco mais da história e do compromisso da Callore através do catálogo digital.';
    @endphp

    <meta property="og:title" content="{{ $og_title }}">
    <meta property="twitter:title" content="{{ $og_title }}" />

    <meta property="og:url" content="{{ Request::url() }}">
    <meta property="twitter:url" content="{{ Request::url() }}" />

    <meta property="og:image" content="{{ $og_image ?? env('APP_URL') . '/img/capa-aquecedor-de-toalhas-callore.jpg' }}">
    <meta property="twitter:image"
        content="{{ $og_image ?? env('APP_URL') . '/img/capa-aquecedor-de-toalhas-callore.jpg' }}" />

    <meta property="twitter:card" content="summary_large_image" />

    <meta name="description" content="{{ $description }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="twitter:description" content="{{ $description }}" />

    <meta charset="utf-8">
    <title>{{ $og_title }}</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="canonical" href="{{ Request::url() }}">

    <!-- Flipbook StyleSheet -->
    <link href="{{ asset('css/dflip.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Icons Stylesheet -->
    <link href="{{ asset('css/themify-icons.min.css') }}" rel="stylesheet" type="text/css">
</head>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P4HZNVCG" defer height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    @include('components.menu')


    <div class="container">
        <div class="row">
            <div class="col-xs-12" style="padding-bottom:30px">
                <!--Normal FLipbook-->
                <div class="_df_book" height="500" webgl="true" backgroundcolor="none"
                    source="{{ asset('books/catalogo-aquecedor-montagem-8.pdf') }}" id="df_manual_book">
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery  -->
    <script src="{{ asset('js/libs/jquery.min.js') }}" type="text/javascript"></script>
    <!-- Flipbook main Js file -->
    <script src="{{ asset('js/dflip.min.js') }}" type="text/javascript"></script>

    @include('templates.footer')
    @include('templates.cookies')

    @if (env('APP_ENV') == 'production')
        <link rel="stylesheet" href="{{ asset('css/styles.min.css?v=4.4') }}">
        <script src="{{ asset('js/home.min.js') }}"></script>
        <script src="{{ asset('js/pop-up.min.js') }}"></script>
    @else
        <link rel="stylesheet" href="{{ asset('css/styles.css?v=4.4') }}">
        <script src="{{ asset('js/home.js') }}"></script>
        <script src="{{ asset('js/pop-up.js') }}"></script>
    @endif

    <style>
        ._df_book {
            height: 80vh !important;
        }
    </style>
</body>

</html>
