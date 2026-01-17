@extends('templates.main', [
    'description' => 'O Aquecedor de Toalhas Callore, fabricado pela WECO, une conforto pós-banho, baixo consumo de energia e design sofisticado. Adaptável a qualquer banheiro e ainda contribui para a redução do impacto ambiental.',
    'og_title' => 'Aquecedor de Toalhas Callore | Conforto, Economia e Qualidade WECO',
])
@section('content')
    @include('flash-message')
    <div class="single-breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>></span>
        <span>Sobre a Callore Aquecedor de Toalhas</span>
    </div>
    <div class="default-page about-page">
        <h1>Sobre a Callore Aquecedor de Toalhas</h1>

        <div class="content-about">
            <div class="col-1">
                <p>O <strong>Aquecedor de Toalhas Callore</strong> é fabricado pela <strong>WECO S/A</strong>, tradicional
                    empresa metalúrgica de Porto Alegre,
                    fundada em 1966 e especializada em caldeiras e soluções de aquecimento. Reconhecida nacionalmente pelo
                    design
                    sofisticado e pela qualidade incomparável, a WECO une inovação e tradição para entregar produtos que
                    elevam o
                    conforto e a funcionalidade do seu dia a dia.</p>

                <p>Desenvolvido para proporcionar <strong>conforto pós-banho</strong> com <strong>baixo consumo de
                        energia</strong>, o Aquecedor de Toalhas Callore
                    também se destaca pela <strong>versatilidade</strong>, ocupando a mesma largura dos porta-toalhas
                    convencionais e oferecendo
                    modelos que se adaptam facilmente a diferentes tamanhos de banheiros.</p>

                <p>Além disso, o produto desempenha um papel importante no <strong>cuidado ambiental</strong>: seu uso ajuda
                    a reduzir a necessidade
                    de lavar toalhas com tanta frequência, diminuindo o consumo de água, energia e produtos químicos
                    utilizados na
                    lavagem — uma escolha inteligente para você e para o planeta.</p>

                <div class="testimony">
                    <p class="text">"Os produtos Callore proporcionam mais qualidade ao seu banho, além de aquecerem e
                        eliminarem a umidade das toalhas, tornam o ambiente mais agradável, confortável e relaxante"</p>
                    <p class="legend">– Cezar Luiz Muller - Industrial - Novo Hamburgo/RS</p>
                </div>
            </div>
            <div class="col-2">
                <img src="{{ asset('img/banheiro-moderno-com-aquecedor-de-toalhas-callore.webp') }}"
                    alt="Banheiro moderno com Aquecedor de Toalhas Callore">
            </div>
        </div>
    </div>
@endsection
