    <section class="revenda-catalogo-home" style="background-image: url({{ asset('img/background.webp') }})">
        <div class="text">
            <h2><strong>Revenda</strong> o Aquecedor de Toalhas Callore</h2>
            <p>O produto certo para elevar a imagem e a qualidade da sua loja.</p>
            <div class="abas">
                <div class="links">
                    <a href="{{ route('aquecedores-de-toalhas-callore') }}" class="bt-secondary-one">Benefícios do produto</a>
                    <a href="{{ route('about') }}" class="bt-secondary-one">Sobre a Callore</a>
                    <a href="{{ route('flip') }}" class="bt-secondary-one">Catálogo digital</a>
                </div>
                <div class="buy-now">
                    <a href="{{ route('contact.revenda') }}"><span class="bt-secondary-one">Comece agora</span><img src="{{ asset('icons/continuar.svg') }}" alt="Começar agora"></a>
                </div>
            </div>
        </div>
        <div class="image-destaque">
            <img src="{{ asset('img/compacto-preto-com-fio--2.webp') }}" alt="Compacto preto com fio">
        </div>
    </section>
