<div class="home-products-2-box">
    <img src="{{ asset('icons/prev.svg') }}" alt="<" class="prev-on-slide" onclick="prevSlideHP(this)">
    <div class="carousel_h_p" id="carousel_h_p">
        @foreach ($products as $product)
            <div class="item">
                <a href="{{ route('products.single', $product->colors[0]->url) }}" class="thumbnail">
                    <figure>
                        <img src="{{ $product->colors[0]->src }}" alt="{{ $product->title }}"
                            title="{{ $product->title }}" width="200">
                    </figure>
                </a>
                <div class="others-colors">
                    @foreach ($product->colors as $color)
                        <a href="javascript:void(0)" onclick="colorSet(this)"
                            url="{{ route('products.single', $color->url) }}" src="{{ $color->src }}">
                            <img src="{{ asset($color->color_img) }}" alt="{{ $color->title }}">
                        </a>
                    @endforeach
                </div>
                <div class="price">
                    <span>R$ {{ number_format($product->discount ?? $product->price, 2, ',', '.') }}</span>
                    <span>ou em até 12x</span>
                </div>

                <div class="bt-actions">
                    <a href="{{ route('products.single', $product->colors[0]->url) }}"
                        class="bt-primary-one">Comprar</a>
                </div>
                <a href="{{ route('products.single', $product->colors[0]->url) }}" class="title">
                    <h3>{{ $product->title }}</h3>
                </a>
            </div>
        @endforeach
    </div>
    <img src="{{ asset('icons/next.svg') }}" alt=">" class="next-on-slide" onclick="nextSlideHP(this)">
</div>
<script>
    function colorSet(e) {
        let product = e.parentElement.parentElement
        product.children[0].children[0].children[0].setAttribute('src', e.getAttribute('src'))
        product.children[0].children[0].children[0].setAttribute('alt', e.getAttribute('alt'))
        product.children[0].setAttribute('href', e.getAttribute('url'))
        product.children[product.children.length - 1].setAttribute('href', e.getAttribute('url'))
        product.children[product.children.length - 2].children[0].setAttribute('href', e.getAttribute('url'))
    }

    const carousel_h_p = document.getElementById('carousel_h_p');
    const item = carousel_h_p.querySelector('.item');

    let startScrollLeft = 0;
    let isScrolling = false;

    carousel_h_p.addEventListener('scroll', () => {
        if (!isScrolling) {
            startScrollLeft = carousel_h_p.scrollLeft;
            isScrolling = true;
        }

        clearTimeout(carousel_h_p._scrollTimeout);

        carousel_h_p._scrollTimeout = setTimeout(() => {
            snapScroll();
            isScrolling = false;
        }, 100); // tempo para detectar fim do scroll
    });

    function snapScroll() {
        const itemWidth = item.offsetWidth;
        const currentScroll = carousel_h_p.scrollLeft;

        const index = Math.round(startScrollLeft / itemWidth);
        const delta = currentScroll - startScrollLeft;

        let targetIndex = index;

        if (Math.abs(delta) > itemWidth * 0.6) {
            targetIndex += delta > 0 ? 1 : -1;
        }

        targetIndex = Math.max(
            0,
            Math.min(targetIndex, carousel_h_p.children.length - 1)
        );

        carousel_h_p.scrollTo({
            left: targetIndex * itemWidth,
            behavior: 'smooth'
        });
    }
</script>
