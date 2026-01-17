<div class="car-mol-23-box banner-callore-box">
    <div class="car-mol-23" id="banner-callore">
        <div class="item">
            <a href="{{ route('products.list') }}">
                <picture>
                    {{-- <source media="(min-width:1920px)" srcset="{{ asset('img/site-6x-sem-juros-1920.webp') }}"> --}}
                    {{-- <source media="(min-width:1366px)" srcset="{{ asset('img/banners/site-callore-aquecedor-de-toalhas-6x-sem-juros.webp') }}"> --}}
                    {{-- <source media="(min-width:1280px)" srcset="{{ asset('img/site-6x-sem-juros-1280.webp') }}"> --}}
                    {{-- <source media="(min-width:1080px)" srcset="{{ asset('img/site-6x-sem-juros-1080.webp') }}"> --}}
                    {{-- <source media="(min-width:800px)" srcset="{{ asset('img/site-6x-sem-juros-800.webp') }}"> --}}
                    <source media="(min-width:600px)" srcset="{{ asset('img/banners/site-callore-aquecedor-de-toalhas-6x-sem-juros.webp') }}">
                    <img src="{{ asset('img/banners/site-callore-aquecedor-de-toalhas-6x-sem-juros-mob.webp') }}"
                        alt="Todo site em 6x Aquecedor de Toalhas Callore"
                        title="Todo site em 6x Aquecedor de Toalhas Callore" style="width: 100%" width="200">
                </picture>
            </a>
        </div>

        <div class="item">
            <a href="{{ route('products.list') }}">
                <picture>
                    {{-- <source media="(min-width:1920px)" srcset="{{ asset('img/beneficios-callore-1920.webp') }}"> --}}
                    {{-- <source media="(min-width:1366px)" srcset="{{ asset('img/beneficios-callore-1920.webp') }}"> --}}
                    {{-- <source media="(min-width:1280px)" srcset="{{ asset('img/beneficios-callore-1280.webp') }}"> --}}
                    {{-- <source media="(min-width:1080px)" srcset="{{ asset('img/beneficios-callore-1080.webp') }}"> --}}
                    {{-- <source media="(min-width:800px)" srcset="{{ asset('img/beneficios-callore-800.webp') }}"> --}}
                    <source media="(min-width:600px)" srcset="{{ asset('img/banners/beneficios-aquecedor-de-toalhas-callore.webp') }}">
                    <img src="{{ asset('img/banners/beneficios-aquecedor-de-toalhas-callore-mob.webp') }}"
                        alt="Benefícios do Aquecedor de Toalhas Callore"
                        title="Benefícios do Aquecedor de Toalhas Callore" style="width: 100%" width="200">
                </picture>
            </a>
        </div>

        <div class="item">
            <a href="{{ route('products.list') }}">
                <picture>
                    {{-- <source media="(min-width:1920px)" srcset="{{ asset('img/banner-modelos-minimailsta.webp') }}"> --}}
                    {{-- <source media="(min-width:1366px)" srcset="{{ asset('img/banner-modelos-minimailsta.webp') }}"> --}}
                    {{-- <source media="(min-width:1280px)" srcset="{{ asset('img/banner-modelos-minimailsta.webp') }}"> --}}
                    {{-- <source media="(min-width:1080px)" srcset="{{ asset('img/banner-modelos-minimailsta.webp') }}"> --}}
                    {{-- <source media="(min-width:800px)" srcset="{{ asset('img/banner-modelos-minimailsta.webp') }}"> --}}
                    <source media="(min-width:600px)" srcset="{{ asset('img/banners/cores-e-modelos-callore-aquecedor-de-toalhas.webp') }}">
                    <img src="{{ asset('img/banners/cores-e-modelos-callore-aquecedor-de-toalhas-mob.webp') }}"
                        alt="Cores e modelos do Aquecedor de Toalhas Callore"
                        title="Cores e modelos do Aquecedor de Toalhas Callore" style="width: 100%" width="200">
                </picture>
            </a>
        </div>

    </div>
</div>

<script>
    configurations_carousel.push({
        id: 1,
        htmlObj: '#banner-callore',
        automatic: false,
        duration: 5000,
        type: 'simple',
        nav: true,
        bubbles: true
    })
</script>
