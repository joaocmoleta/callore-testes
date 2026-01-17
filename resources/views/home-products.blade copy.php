<div class="car-mol-23-multi-box" id="products-home-box">
    <div class="car-mol-23-multi" id="products-home">
        @foreach ($products as $product)
            <div class="item">
                <div class="product">
                    {{-- {{ $product->colors[0]->src }} --}}
                    <a href="{{ route('products.single', $product->colors[0]->url) }}" class="thumbnail">
                        <figure>
                            <img src="{{ $product->colors[0]->src }}" alt="{{ $product->title }}"
                                title="{{ $product->title }}" width="200">
                        </figure>
                    </a>
                    <a href="{{ route('products.single', $product->colors[0]->url) }}" class="title">
                        <h3>{{ $product->title }}</h3>
                    </a>
                    @if (count($product->colors) > 1)
                        <div class="others-colors">
                            @foreach ($product->colors as $color)
                                <span title="{{ $color->title }}" onclick="colorSet(this)" class="{{ $color->color }}"
                                    alt="{{ $product->title }} | {{ $color->title }}"
                                    url="{{ route('products.single', $color->url) }}"
                                    src="{{ $color->src }}"></span>
                            @endforeach
                        </div>
                    @endif
                    <div class="price">
                        <span>R$ {{ number_format($product->discount ?? $product->price, 2, ',', '.') }}</span>
                        <span>ou em até 12x</span>
                    </div>

                    <div class="bt-actions">
                        <a href="{{ route('products.single', $product->colors[0]->url) }}"
                            class="bt-primary-one">Comprar</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<script>
    function colorSet(e) {
        let product = e.parentElement.parentElement
        product.children[0].children[0].children[0].setAttribute('src', e.getAttribute('src'))
        product.children[0].children[0].setAttribute('alt', e.getAttribute('alt'))
        product.children[0].setAttribute('href', e.getAttribute('url'))
        product.children[1].setAttribute('href', e.getAttribute('url'))
        product.children[product.children.length - 1].children[0].setAttribute('href', e.getAttribute('url'))
    }

    configurations_carousel.push({
        id: 600,
        htmlObj: '#products-home',
        automatic: false,
        duration: 2000,
        type: 'multi',
        nav: true,
        bubbles: true
    })
</script>
