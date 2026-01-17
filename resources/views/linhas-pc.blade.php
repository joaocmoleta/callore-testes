<div class="products">
    @foreach ($products as $item)
        <div class="product">
            <a href="{{ route('products.single', $item['slug']) }}" class="thumbnail">
                <figure>
                    <img src="{{ asset($item['thumbnail']) }}" alt="{{ $item['title'] }}" title="{{ $item['title'] }}"
                        width="200">
                </figure>
            </a>
            <a href="{{ route('products.single', $item['slug']) }}" class="title-lines">
                <h3>{{ $item['title'] }}</h3>
            </a>
            <div class="abstract">
                <p>{{ $item['abstract'] }}</p>
            </div>
            <div class="price">
                <span>A partir de R$ {{ $item['price'] }}</span>
                <span>ou em até 12x</span>
            </div>
            <a href="{{ route('products.single', $item['slug']) }}" class="bt-primary-one">Ver</a>
        </div>
    @endforeach
</div>
