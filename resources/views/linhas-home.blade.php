{{-- <section class="home-products">
    <h1>Callore Aquecedores de Toalhas</h1>
    <h2>Disponível em três linhas + dois modelos especiais no cromado</h2>
    <p class="call-depoimentos">Um toalheiro aquecido para seu <img src="{{ asset('img/estilo.webp') }}" width="100"
            style="margin-bottom: -3px"></p>
</section> --}}

<div class="home-line-2-box">
    <div class="home-line-2">
        <div class="chamada-para-modelos">
            <p>Toalhas secas</p>
            <p>Mais higiene para seu lar</p>
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

            {{-- <a href="{{ route('products.list') }}" class="bt-primary-one">Confira todos os modelos</a> --}}

        </div>
        @php
            $teste = '[{"title":"Versátil","price":1754.54,"discount":null,"qtd":1,"colors":[{"color":"black","title":"Preto","url":"aquecedor-de-toalhas-callore-versatil-preto-127v","src":"img/aquecedor-de-toalhas-versatil-preto.webp","color_img":"/img/acabamento-aquecedor-de-toalhas-callore-preto.webp"},{"color":"white","title":"Branco","url":"aquecedor-de-toalhas-callore-versatil-branco-127v","src":"img/aquecedor-de-toalhas-versatil-branco.webp","color_img":"/img/acabamento-aquecedor-de-toalhas-callore-branco.webp"},{"color":"bege","title":"Bege","url":"aquecedor-de-toalhas-callore-versatil-bege-127v","src":"img/aquecedor-de-toalhas-versatil-bege.webp","color_img":"/img/acabamento-aquecedor-de-toalhas-callore-bege.webp"}],"other_infos":[{"name":"v127220","text":"127ou220V"}]},{"title":"Compacto","price":1928.62,"discount":null,"qtd":1,"colors":[{"color":"black","title":"Preto","url":"aquecedor-de-toalhas-callore-compacto-preto-127v","src":"img/aquecedor-de-toalhas-compacto-preto.webp","color_img":"/img/acabamento-aquecedor-de-toalhas-callore-preto.webp"},{"color":"white","title":"Branco","url":"aquecedor-de-toalhas-callore-compacto-branco-127v","src":"img/aquecedor-de-toalhas-compacto-branco.webp","color_img":"/img/acabamento-aquecedor-de-toalhas-callore-branco.webp"},{"color":"bege","title":"Bege","url":"aquecedor-de-toalhas-callore-compacto-bege-127v","src":"img/aquecedor-de-toalhas-compacto-bege.webp","color_img":"/img/acabamento-aquecedor-de-toalhas-callore-bege.webp"}],"other_infos":[{"name":"v127220","text":"127ou220V"}]},{"title":"Família","price":2328.40,"discount":null,"qtd":1,"colors":[{"color":"black","title":"Preto","url":"aquecedor-de-toalhas-callore-familia-preto-127v","src":"img/aquecedor-de-toalhas-familia-preto.webp","color_img":"/img/acabamento-aquecedor-de-toalhas-callore-preto.webp"},{"color":"white","title":"Branco","url":"aquecedor-de-toalhas-callore-familia-branco-127v","src":"img/aquecedor-de-toalhas-familia-branco.webp","color_img":"/img/acabamento-aquecedor-de-toalhas-callore-branco.webp"},{"color":"bege","title":"Bege","url":"aquecedor-de-toalhas-callore-familia-bege-127v","src":"img/aquecedor-de-toalhas-familia-bege.webp","color_img":"/img/acabamento-aquecedor-de-toalhas-callore-bege.webp"}],"other_infos":[{"name":"v127220","text":"127ou220V"}]},{"title":"Stilo8","price":2613,"discount":null,"qtd":0,"colors":[{"color":"chromo","title":"Cromado","url":"aquecedor-de-toalhas-callore-stilo-8-127v","src":"img/aquecedor-de-toalhas-stilo-8.webp","color_img":"/img/acabamento-aquecedor-de-toalhas-callore-cromado.webp"}],"other_infos":[{"name":"v127220","text":"127ou220V"}]},{"title":"Stilo10","price":3224.54,"discount":null,"qtd":0,"colors":[{"color":"chromo","title":"Cromado","url":"aquecedor-de-toalhas-callore-stilo-10-127v","src":"img/aquecedor-de-toalhas-stilo-10.webp","color_img":"/img/acabamento-aquecedor-de-toalhas-callore-cromado.webp"}],"other_infos":[{"name":"v127220","text":"127ou220V"}]},{"title":"Suporte Móvel com Rodinhas para Aquecedor Callore","price":460,"discount":null,"qtd":1,"colors":[{"color":"black","title":"Preto","url":"suporte-de-chao-com-rodinhas-preto","src":"img/suporte-movel-com-rodinhas-preto-2.webp","color_img":"/img/acabamento-aquecedor-de-toalhas-callore-preto.webp"},{"color":"white","title":"Branco","url":"suporte-de-chao-com-rodinhas-branco","src":"img/suporte-movel-com-rodinhas-branco.webp","color_img":"/img/acabamento-aquecedor-de-toalhas-callore-branco.webp"},{"color":"bege","title":"Bege","url":"suporte-de-chao-com-rodinhas-bege","src":"img/suporte-movel-com-rodinhas-bege.webp","color_img":"/img/acabamento-aquecedor-de-toalhas-callore-bege.webp"}]},{"title":"Suporte Móvel para Aquecedor Callore","price":200,"discount":null,"qtd":1,"colors":[{"color":"black","title":"Preto","url":"suporte-de-chao-sem-rodinhas-preto","src":"img/suporte-movel-preto-3.webp","color_img":"/img/acabamento-aquecedor-de-toalhas-callore-preto.webp"},{"color":"white","title":"Branco","url":"suporte-de-chao-sem-rodinhas-branco","src":"img/suporte-movel-branco.webp","color_img":"/img/acabamento-aquecedor-de-toalhas-callore-branco.webp"},{"color":"bege","title":"Bege","url":"suporte-de-chao-sem-rodinhas-bege","src":"img/suporte-movel-bege.webp","color_img":"/img/acabamento-aquecedor-de-toalhas-callore-bege.webp"}]}]';
            // dd(json_decode($teste)[0]->colors[0]->xyz);
            // foreach (json_decode($teste) as $item) {
            //     foreach ($item->colors as $color) {
            //         print_r($color->xyz);
            //     }
            // }
        @endphp
        
        @include('home-products', [
            'products' => json_decode($teste)
        ])
    </div>
</div>
<script>
    configurations_carousel.push({
        id: 3,
        htmlObj: '#hd-call',
        automatic: 'all',
        duration: 1500,
        type: 'simple'
    })
</script>