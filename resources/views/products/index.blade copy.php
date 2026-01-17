@extends('templates.main', [
    'og_title' => 'Produtos da Callore Aquecedores de Toalhas',
    'description' => 'Procurando por aquecedor de toalhas? Confira os modelos produzidos pela Callore. Facilidade no pagamento e entrega. Baixo consumo de energia.',
])
@section('content')
    <section class="products-list">
        <h1>Produtos da Callore Aquecedores de Toalhas</h1>
        <div class="products">
            @foreach ($products as $item)
                <div class="product">
                    <a href="{{ route('products.single', $item->slug) }}" class="thumbnail">
                        <figure>
                            <img src="{{ asset(json_decode($item->images, 0)[0]) }}" alt="{{ $item->title }}"
                                title="{{ $item->title }}" width="200">
                        </figure>
                    </a>
                    <a href="{{ route('products.single', $item->slug) }}" class="title">
                        <h2>{{ $item->title }}</h2>
                    </a>
                    <div class="price">
                        <span>R$ {{ number_format($item->value, 2, ',', '.') }}</span>
                        <span>ou em até 12x</span>
                    </div>
                    <div class="bt-actions">
                        @if ($item->qtd > 0)
                            <form action="{{ route('carts.add', $item->id) }}" method="post" class="buy">
                                @method('PUT')
                                @csrf

                                <input type="hidden" name="qtd" value="1" class="bt-primary-one add">
                                <button class="bt-primary-one">Comprar</button>
                            </form>
                            {{-- <a href="{{ route('products.single', $item->slug) }}" class="bt-primary-one">Ver</a> --}}
                        @else
                            <span class="bt-primary-one">Esgotado</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
