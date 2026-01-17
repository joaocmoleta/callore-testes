@extends('templates.main', [
    'description' => 'Procurando por aquecedor de toalhas? Confira os modelos produzidos pela Callore. Facilidade no pagamento e entrega. Baixo consumo de energia.',
    'og_title' => 'Callore Aquecedores de Toalhas',
])
@section('content')
    @include('flash-message')

    {{-- @include('home-video') --}}
    {{-- @include('home-destaque') --}}
    @include('home-video-summer')
    {{-- @include('black-friday-2023') --}}

    <section class="home-products">
        <h1>Callore Aquecedores de Toalhas</h1>
        <h2>Disponível em três linhas</h2>
        <p class="call-depoimentos">Um toalheiro aquecido para seu estilo</p>
    </section>

    <div class="home-line-2-box">
        <div class="home-line-2">
        <div class="chamada-para-modelos">
            <h4>Toalhas secas <strong>Mais higiene para seu lar</strong></h4>
                <a href="{{ route('products.list') }}" class="bt-primary-one">Confira todos os modelos</a>
        </div>
        @include('home-products', [
            'products' => [
                [
                    'slug' => 'aquecedor-de-toalhas-callore-versatil-branco-127v',
                    'thumbnail' => '/img/Aquecedor Callore Versátil - Preto.webp',
                    'title' => 'Linha Callore Versátil',
                    'abstract' => '8 tubos | 150W | 50 X 74cm',
                    'price' => '1.040,00',
                ],
                [
                    'slug' => 'aquecedor-de-toalhas-callore-compacto-bege-127v',
                    'thumbnail' => '/img/Aquecedor Callore Compacto - Preto.webp',
                    'title' => 'Linha Callore Compacto',
                    'abstract' => '12 tubos | 200W | 50 X 69cm',
                    'price' => '1.210,00',
                ],
                [
                    'slug' => 'aquecedor-de-toalhas-callore-familia-branco-127v',
                    'thumbnail' => '/img/Aquecedor Callore Família - Preto - medidas.webp',
                    'title' => 'Linha Callore Família',
                    'abstract' => '15 tubos | 250W | 50 X 94cm',
                    'price' => '1.455,00',
                ],
            ],
        ])
        </div>
    </div>

    @include('banner-home')
    @include('depoimentos-home')
    @include('about')
@endsection
