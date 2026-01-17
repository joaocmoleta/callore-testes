<section class="home-destaque">
    <picture class="hd-background">
        <source media="(min-width:900px)" srcset="{{ asset('/img/banheiro-lindo-aquecedor-de-toalhas-callore.webp') }}">
        <source media="(min-width:601px)" srcset="{{ asset('/img/banheiro-lindo-aquecedor-de-toalhas-callore.webp') }}">
        <img src="{{ asset('/img/banheiro-lindo-aquecedor-de-toalhas-callore.webp') }}"
            alt="Banheiro lindo com o Aquecedor de Toalhas Callore"
            title="Banheiro lindo com o Aquecedor de Toalhas Callore" style="width: 100%" width="200">
    </picture>
    <div class="hd-h1-hd-image-button">
        <div class="hd-text">
            <div class="car-mol-23-box">
                <div class="car-mol-23 hd-call" id="hd-call">
                    <div class="item">Seca</div>
                    <div class="item">Evita mofo</div>
                    <div class="item">Prático</div>
                    <div class="item">Econômico</div>
                    <div class="item">Moderno</div>
                    <div class="item">Seguro</div>
                    <div class="item">Baixo consumo</div>
                    <div class="item">Inibe odor</div>
                    <div class="item">Adaptável</div>
                    <div class="item">Confortável</div>
                </div>
            </div>

            <h1 class="hd-h1">Callore Aquecedores de Toalhas</h1>
            <h2>O Toalheiro Térmico ideal para seu lar</h2>
        </div>
        <div class="hd-image-button">
            <div class="car-mol-23-multi-box" id="carousel-destaques-produtos-box" >
                <div class="car-mol-23-multi" id="carousel-destaques-produtos">
                    <div class="item">
                        <a href="{{ route('products.single', 'aquecedor-de-toalhas-callore-familia-bege-127v') }}">
                            <img src="{{ asset('/img/Aquecedor Familia Bege 15 barras.png') }}" alt="Aquecedor de Toalhas Callore Família Bege">
                        </a>
                    </div>
                    <div class="item">
                        <a href="{{ route('products.single', 'aquecedor-de-toalhas-callore-stilo-8-127v') }}">
                            <img src="{{ asset('/img/Aquecedor Stilo 8 barras.png') }}" alt="Aquecedor de Toalhas Callore Stilo 8">
                        </a>
                    </div>
                    <div class="item">
                        <a href="{{ route('products.single', 'aquecedor-de-toalhas-callore-familia-branco-127v') }}">
                            <img src="{{ asset('/img/Aquecedor Familia Branca 15 barras.png') }}" alt="Aquecedor de Toalhas Callore Família Branco">
                        </a>
                    </div>
                    <div class="item">
                        <a href="{{ route('products.single', 'aquecedor-de-toalhas-callore-stilo-10-127v') }}">
                            <img src="{{ asset('/img/Aquecedor Stilo 10 barras.png') }}" alt="Aquecedor de Toalhas Callore Stilo 10">
                        </a>
                    </div>
                    <div class="item">
                        <a href="{{ route('products.single', 'aquecedor-de-toalhas-callore-familia-preto-127v') }}">
                            <img src="{{ asset('/img/Aquecedor Familia Preto 15 barras.png') }}" alt="Aquecedor de Toalhas Callore Família Preto">
                        </a>
                    </div>
                </div>
            </div>
            <p><a href="{{ route('products.list') }}" class="bt-primary-one hd-button">Adquira já</a></p>
        </div>
    </div>
</section>

<script>
    configurations_carousel.push({
        id: 3,
        htmlObj: '#hd-call',
        automatic: 'all',
        duration: 1500,
        type: 'simple'
    })
    configurations_carousel.push({
        id: 'carousel-destaques-produtos',
        htmlObj: '#carousel-destaques-produtos',
        automatic: false,
        duration: 2000,
        type: 'multi',
        nav: true,
        bubbles: true,
    })
</script>
