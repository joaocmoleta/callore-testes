<section class="black-friday-23">
    <div class="car-mol-23-box banner-black-friday-23-box">
        <div class="car-mol-23" id="banner-black-friday-23">
            <div class="item">
                <a href="{{ route('products.list') }}">
                    <picture>
                        <source media="(min-width:600px)"
                            srcset="{{ asset('img/black-friday-2023-callore-aquecedores.webp') }}">
                        <img src="{{ asset('img/black-friday-2023-callore-aquecedores-mobile.webp') }}"
                            alt="Black friday Callore Aquecedores de Toalhas"
                            title="Black friday Callore Aquecedores de Toalhas" style="width: 100%" width="200">
                    </picture>
                </a>
            </div>

            <div class="item">
                <a href="{{ route('products.list') }}">
                    <picture>
                        <source media="(min-width:600px)"
                            srcset="img/black-friday-2023-modelos-callore-aquecedores.webp">
                        <img src="img/black-friday-2023-modelos-callore-aquecedores-mobile.webp"
                            alt="Black friday Alguns modelos Aquecedor de Toalhas Callore"
                            title="Black friday Alguns modelos Aquecedor de Toalhas Callore" style="width: 100%"
                            width="200">
                    </picture>
                </a>
            </div>
        </div>
    </div>
</section>
<script>
    configurations_carousel.push({
        id: 500,
        htmlObj: '#banner-black-friday-23',
        automatic: false,
        duration: 4000,
        type: 'simple',
        nav: true,
        bubbles: true
    })
</script>
