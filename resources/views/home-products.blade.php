<div class="home-products-2-box">
    <img src="{{ asset('icons/prev.svg') }}" alt="<" class="prev-on-slide" onclick="prevSlideHP(this)">
    <div class="home-products-2" onscroll="manualScrollHP(this)">
        <div class="home-products-content">
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
    </div>
    <img src="{{ asset('icons/next.svg') }}" alt=">" class="next-on-slide" onclick="nextSlideHP(this)">
</div>
<script>
    // function scrollHideNext(e) {
    //     if (e.scrollLeft > 2) {
    //         e.children[1].style.display = 'none'
    //     } else {
    //         e.children[1].style.display = 'flex'
    //     }
    // }


    function prevSlideHP(e) {
        let gallery_box = e.parentElement.children[1]
        let item = gallery_box.children[0].children[0]

        gallery_box.scrollLeft = gallery_box.scrollLeft - item.offsetWidth

        e.parentElement.children[2].style.opacity = 0.5

        if (gallery_box.scrollLeft <= item.offsetWidth) {
            e.style.opacity = 0.2
        }
    }

    function nextSlideHP(e) {
        let gallery_box = e.parentElement.children[1]
        let item = gallery_box.children[0].children[0]

        gallery_box.scrollLeft = gallery_box.scrollLeft + item.offsetWidth

        e.parentElement.children[0].style.opacity = 0.5

        if (gallery_box.scrollLeft > gallery_box.children[0].offsetWidth * .6) {
            e.style.opacity = 0.2
        }
    }

    function manualScrollHP(e) {
        if (e.scrollLeft > e.children[0].offsetWidth * .568) {
            e.parentElement.children[2].style.opacity = 0.2
        } else {
            e.parentElement.children[2].style.opacity = 0.5
        }

        if (e.scrollLeft == 0) {
            e.parentElement.children[0].style.opacity = 0.2
        } else {
            e.parentElement.children[0].style.opacity = 0.5
        }
    }

    function manualScrollHPStart(e) {
        // gravar scrollLeft atual
        console.log('iniciado scroll')
        scrollLeftHP = e.scrollLeft
    }

    function manualScrollHPEnd(e) {
        console.log(`Scrool anteriro ${scrollLeftHP}`)
        console.log(`Scrool atual ${e.scrollLeft}`)


    }

    function colorSet(e) {
        let product = e.parentElement.parentElement
        product.children[0].children[0].children[0].setAttribute('src', e.getAttribute('src'))
        product.children[0].children[0].children[0].setAttribute('alt', e.getAttribute('alt'))
        product.children[0].setAttribute('href', e.getAttribute('url'))
        product.children[product.children.length - 1].setAttribute('href', e.getAttribute('url'))
        product.children[product.children.length - 2].children[0].setAttribute('href', e.getAttribute('url'))
    }


    // let home_products_2 = document.querySelector('.home-products-2')
    // let scrollStarted = false
    // let scrollLeftHP = 0

    // home_products_2.addEventListener('scroll', function(e) {
    //     console.log(e)
    //     e.preventDefault()

    //     if (scrollStarted === false) {
    //         scrollStarted = true;
    //     }
    // })

    // home_products_2.addEventListener('scrollend', function(e) {
    //     scrollStarted = false
    //     let item_width = e.target.children[0].children[0].offsetWidth
    //     let diferenca_scroll = e.target.scrollLeft - scrollLeftHP

    //     // console.log(`scroll left atual: ${e.target.scrollLeft}`)
    //     // console.log(`Diferença do anterior ${diferenca_scroll}`)
    //     // console.log(`60% do objeto: ${60 * item_width / 100}`)

    //     // if(diferenca_scroll > (60 * item_width / 100)) {
    //     //     e.target.scrollLeft = item_width
    //     // } else {
    //     //     e.target.scrollLeft = e.target.scrollLeft - diferenca_scroll
    //     // }

    //     scrollLeftHP = e.target.scrollLeft
    // })

</script>
