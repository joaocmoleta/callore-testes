<!DOCTYPE html>
<html lang="pt-br">

<head>
    @if (env('APP_ENV') == 'production')
        @include('templates.tags-rastreamento')
    @endif

    @if (isset($nofollow))
        <meta name="robots" content="nofollow">
    @endif
    @if (isset($noindex))
        <meta name="robots" content="noindex">
    @endif

    @if (isset($og_title))
        <meta property="og:title" content="{{ $og_title }}">
        <meta property="twitter:title" content="{{ $og_title }}" />
    @endif

    <meta property="og:url" content="{{ Request::url() }}">
    <meta property="twitter:url" content="{{ Request::url() }}" />

    <meta property="og:image"
        content="{{ $og_image ?? env('APP_URL') . '/img/capa-aquecedor-de-toalhas-callore.jpg' }}">
    <meta property="twitter:image"
        content="{{ $og_image ?? env('APP_URL') . '/img/capa-aquecedor-de-toalhas-callore.jpg' }}" />

    <meta property="twitter:card" content="summary_large_image" />

    @if (isset($product_brand))
        <meta property="product:brand" content="{{ $product_brand }}">
    @endif
    @if (isset($product_availability))
        <meta property="product:availability" content="{{ $product_availability }}">
    @endif
    @if (isset($product_condition))
        <meta property="product:condition" content="{{ $product_condition }}">
    @endif
    @if (isset($product_price_amount))
        <meta property="product:price:amount" content="{{ $product_price_amount }}">
    @endif
    @if (isset($product_price_currency))
        <meta property="product:price:currency" content="{{ $product_price_currency }}">
    @endif
    @if (isset($product_retailer_item_id))
        <meta property="product:product_retailer_item_id" content="{{ $product_retailer_item_id }}">
    @endif
    @if (isset($product_item_group_id))
        <meta property="product:item_group_id" content="{{ $product_item_group_id }}">
    @endif

    @if (isset($description))
        <meta name="description" content="{{ $description }}">
        <meta property="og:description" content="{{ $description }}">
        <meta property="twitter:description" content="{{ $description }}" />
    @endif

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $og_title ?? env('APP_NAME') }}</title>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="canonical" href="{{ Request::url() }}">

    @include('templates.css-acima-da-borda')
</head>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P4HZNVCG" defer height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <div class="loader-box-page">
        <span class="loader-page"></span>
    </div>

    <script>
        let configurations_carousel = []
    </script>

    {{-- @include('templates.top-bar', ['class' => 'bar-one'])
    @include('templates.top-bar', ['class' => 'bar-two']) --}}


    @include('components.menu')

    <main role="main">@yield('content')</main>

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
</body>

</html>
