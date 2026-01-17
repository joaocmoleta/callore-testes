<div class="car-mol-23-box" id="similiars-box-mob">
    <div class="car-mol-23 similiars" id="similiars-mob">
        @foreach ($similiars as $item)
            <div class="item">
                <div class="product">
                    <a href="{{ route('products.single', $item->slug) }}" class="thumbnail">
                        <figure>
                            <img src="{{ json_decode($item->images, 0)[0] }}" alt="{{ $item->title }}" alt="{{ $item->title }}">
                        </figure>
                    </a>
                    <a href="{{ route('products.single', $item->slug) }}" class="title">
                        <h3>{{ $item->title }}</h3>
                    </a>
                    <div class="price">
                        <span>R$ {{ number_format($item->value, 2, ',', '.') }}</span>
                        <span>ou em até 12x</span>
                    </div>
                    <div class="bt-actions">
                        <form action="{{ route('carts.add', $item->id) }}" method="post" class="buy">
                            @method('PUT')
                            @csrf
    
                            <input type="hidden" name="qtd" value="1" class="bt-primary-one add">
                            <button class="bt-primary-one">Comprar</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<script>
    configCarousel('similiars-mob', true, 1000, 4000, true);
</script>
