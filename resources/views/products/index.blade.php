@extends('templates.main', [
    'og_title' => 'Produtos da Callore Aquecedores de Toalhas',
    'description' => 'Procurando por aquecedor de toalhas? Confira os modelos produzidos pela Callore. Facilidade no pagamento e entrega. Baixo consumo de energia.',
])
@section('content')
    @include('flash-message')
    <div class="single-breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>></span>
        <span>Aquecedores de Toalhas Callore</span>
    </div>
    <section class="products-list">
        <h1>Aquecedores de Toalhas Callore</h1>
        {{-- <p class="chamada-produtos">Escolha o modelo e a cor perfeitos
            para o seu espaço. Se precisar de ajuda, entre em contato e encontre o produto ideal para você!</p> --}}

        <div class="products">
            @foreach ($products as $product)
                <div class="product">
                    <a href="{{ route('products.single', $product->colors[0]->url) }}" class="title">
                        <h2>{{ $product->title }}</h2>
                    </a>
                    <a href="{{ route('products.single', $product->colors[0]->url) }}">
                        <img src="{{ $product->colors[0]->src }}"
                            alt="{{ $product->title }} | {{ $product->colors[0]->title }}">
                    </a>
                    @if (count($product->colors) > 1)
                        <div class="others-colors">
                            @foreach ($product->colors as $color)
                                @if (isset($color->pick_url))
                                    <span title="{{ $color->title }}" onclick="colorSet(this)"
                                        alt="{{ $product->title }} | {{ $color->title }}"
                                        url="{{ route('products.single', $color->url) }}" src="{{ $color->src }}">
                                        <img src="{{ asset($color->pick_url) }}"
                                            alt="Acabamento na cor {{ $color->title }}">
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    @endif
                    <div class="price">
                        <span>R$ {{ number_format($product->discount ?? $product->price, 2, ',', '.') }}</span>
                        <span>ou em até 12x</span>
                    </div>
                    @if (isset($product->other_infos))
                        @foreach ($product->other_infos as $others)
                            <span class="{{ $others->name }}">{{ $others->text }}</span>
                        @endforeach
                    @endif
                    <div class="bt-actions">
                        <a href="{{ route('products.single', $product->colors[0]->url) }}"
                            class="bt-primary-one">Comprar</a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
<script>
    function colorSet(e) {
        let product = e.parentElement.parentElement
        product.children[0].children[0].setAttribute('src', e.getAttribute('src'))
        product.children[0].children[0].setAttribute('alt', e.getAttribute('alt'))
        product.children[0].setAttribute('href', e.getAttribute('url'))
        product.children[product.children.length - 2].children[0].setAttribute('href', e.getAttribute('url'))
        product.children[product.children.length - 1].setAttribute('href', e.getAttribute('url'))
    }
</script>
