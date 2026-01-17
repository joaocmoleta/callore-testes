@extends('templates.main')
@section('content')
    <div class="single-message">
        @include('flash-message')
    </div>
    <section class="home-products">
        <h2>Produtos</h2>
    </section>
    <section class="home-products">
        @foreach ($products as $item)
            <div class="product">
                <a href="{{ route('products.single', $item->slug) }}" class="thumbnail">
                    <figure>
                        <img src="{{ json_decode($item->images, 0)[0] }}" alt="{{ $item->title }}">
                    </figure>
                </a>
                <a href="{{ route('products.single', $item->slug) }}" class="title">
                    <h3>{{ $item->title }}</h3>
                </a>
                <div class="price">
                    <span>R$ {{ number_format($item->value, 2, ',', '.') }}</span>
                    <span>em 12x R$ {{ number_format($item->value / 12, 2, ',', '.') }}</span>
                </div>
                <div class="bt-actions">
                    <form action="{{ route('carts.add', $item->id) }}" method="post" class="buy">
                        @method('PUT')
                        @csrf

                        <input type="hidden" name="qtd" value="1" class="bt-primary-one add">
                        <button class="bt-primary-one">Comprar</button>
                    </form>
                    {{-- <a href="{{ route('products.single', $item->slug) }}" class="bt-primary-one">Ver</a> --}}
                </div>
            </div>
        @endforeach
    </section>
@endsection
