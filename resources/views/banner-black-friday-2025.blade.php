<div class="car-mol-23-box banner-callore-box">
    <div class="car-mol-23" id="banner-callore">
        <div class="item">
            <a href="{{ route('products.list') }}">
                <picture>
                    <source media="(min-width:2560px)" srcset="{{ asset('img/banner-black-friday-2025/banner-black-friday-faixas-1.webp') }}">
                    <source media="(min-width:1920px)" srcset="{{ asset('img/banner-black-friday-2025/banner-black-friday-faixas-1-1920.webp') }}">
                    <source media="(min-width:1366px)" srcset="{{ asset('img/banner-black-friday-2025/banner-black-friday-faixas-1-1366.webp') }}">
                    <source media="(min-width:1024px)" srcset="{{ asset('img/banner-black-friday-2025/banner-black-friday-faixas-1-1024.webp') }}">
                    <img src="{{ asset('img/banner-black-friday-2025/banner-black-friday-faixas-1-retrato.webp') }}"
                        alt="Oferta especial - Black friday 2025 20% off"
                        title="Oferta especial - Black friday 2025 20% off" style="width: 100%" width="200">
                </picture>
            </a>
        </div>
        <div class="item">
            <a href="{{ route('products.list') }}">
                <picture>
                    <source media="(min-width:2560px)" srcset="{{ asset('img/banner-black-friday-2025/banner-black-friday-faixas-2.webp') }}">
                    <source media="(min-width:1920px)" srcset="{{ asset('img/banner-black-friday-2025/banner-black-friday-faixas-2-1920.webp') }}">
                    <source media="(min-width:1366px)" srcset="{{ asset('img/banner-black-friday-2025/banner-black-friday-faixas-2-1366.webp') }}">
                    <source media="(min-width:1024px)" srcset="{{ asset('img/banner-black-friday-2025/banner-black-friday-faixas-2-1024.webp') }}">
                    <img src="{{ asset('img/banner-black-friday-2025/banner-black-friday-retrato-mix.webp') }}"
                        alt="Black friday 2025 20% off Pix - 5% off Cartão"
                        title="Black friday 2025 20% off Pix - 5% off Cartão" style="width: 100%" width="200">
                </picture>
            </a>
        </div>
    </div>
</div>

<script>
    configurations_carousel.push({
        id: 1,
        htmlObj: '#banner-callore',
        automatic: true,
        duration: 5000,
        type: 'simple',
        nav: true,
        bubbles: true
    })
</script>
