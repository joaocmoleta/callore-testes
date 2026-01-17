@extends('templates.main', [
    'description' => 'Procurando por aquecedor de toalhas? Confira os modelos produzidos pela Callore. Facilidade no pagamento e entrega. Baixo consumo de energia.',
    'og_title' => 'Callore Aquecedores de Toalhas',
])
@section('content')
    @include('flash-message')

    <section class="examples">
        <h1>Aquecedores de Toalhas Callore</h1>
        <h2>Fique por dentro de todos os detalhes</h2>
    </section>

    <section class="one-line">
        <figure>
            <img src="{{ asset('img/praticidade-aquecedores-de-toalhas-callore.webp') }}"
                alt="Praticidade Aquecedor de Toalhas Callore" width="200">
        </figure>
        <div class="text">
            <h2>Praticidade</h2>
            <p>A linha de aquecedores de toalhas Callore foi cuidadosamente projetada para oferecer praticidade,
                durabilidade e conforto. Cada modelo possui um design moderno e sofisticado, se integrando perfeitamente a
                decoração de qualquer ambiente, sua versatilidade permite se adaptar a qualquer espaço, inclusive banheiros
                e apartamentos compactos, proporcionando um toque incomparável de estilo e requinte.</p>
            <p><a href="{{ route('products.list') }}" class="bt-primary-one">Confira os modelos</a></p>
        </div>
    </section>

    <section class="two-line">
        <div class="text">
            <h2>Sustentabilidade</h2>
            <p>Além do conforto, nossos aquecedores de toalhas também são amigos do meio ambiente e do seu bolso, graças à
                sua <strong>eficiência energética</strong>, o consumo elétrico é equivalente ao de uma lâmpada comum. Além
                disso, ao optar pela reutilização das toalhas, é possível espaçar os intervalos entre as lavagens,
                resultando em uma redução significativa na quantidade de efluentes (resíduos químicos, como detergente)
                liberados durante esse processo. Essa combinação inteligente de recursos proporciona benefícios tanto
                econômicos quanto ambientais, permitindo o uso sustentável das toalhas.</p>
            <p><a href="{{ route('products.list') }}" class="bt-primary-one">Confira os modelos</a></p>
        </div>
        <figure>
            <img src="{{ asset('img/sustentabilidade-aquecedor-de-toalhas-callore.webp') }}"
                alt="Sustentabilidade Aquecedor de Toalhas Callore" width="200">
        </figure>
    </section>

    <section class="one-line">
        <figure>
            <img src="{{ asset('img/saude-aquecedor-de-toalhas-callore.webp') }}" alt="Saúde Aquecedor de Toalhas Callore"
                width="200">
        </figure>
        <div class="text">
            <h2>Saúde</h2>
            <p>Os aquecedores de toalhas da Callore <strong>podem ser utilizados o ano todo inclusive no verão, ajudando a
                    reduzir a umidade excessiva no ambiente, prevenindo a proliferação de fungos e bactérias, causadores de
                    mau cheiro nas toalhas</strong>. Elaborado com um sistema de secagem rápido e eficaz, seca toalhas, sem
                ressecar ou danificar os tecidos. Desse modo, você pode desfrutar de toalhas higienizadas, macias, secas e
                prontas para uso a qualquer momento.</p>
            <p><a href="{{ route('products.list') }}" class="bt-primary-one">Confira os modelos</a></p>
        </div>
    </section>

    <section class="two-line">
        <div class="text">
            <h2>Mobilidade</h2>
            <p>O aquecedor de toalhas Callore é o único que possui a opção de troca da resistência, Isso significa que, caso
                o cliente adquira um aquecedor de 127V e mude para uma região de 220V, ele pode simplesmente trocar a
                resistência, em vez de precisar substituir todo o aparelho, como é comum em outras marcas. Essa
                funcionalidade garante maior versatilidade e economia para o cliente, evitando a necessidade de adquirir um
                novo aquecedor quando ocorrem mudanças na voltagem. Além disso, o aquecedor de toalhas Callore oferece
                mobilidade, permitindo que seja movimentado para diferentes ambientes, como a área de serviço, por exemplo,
                adquirindo os suportes de chão que são vendidos separadamente. Lembrando que todos os nossos aparelhos
                acompanham suportes para fixação na parede, que são de fácil instalação.</p>
            <p><a href="{{ route('products.list') }}" class="bt-primary-one">Confira os modelos</a></p>
        </div>
        <figure>
            <img src="{{ asset('img/mobilidade-aquecedores-de-toalhas-callore.webp') }}"
                alt="Mobilidade Aquecedor de Toalhas Callore" width="200">
        </figure>
    </section>

    <section class="one-line">
        <figure>
            <img src="{{ asset('img/qualidade-aquecedores-de-toalhas-callore.webp') }}"
                alt="Saúde Aquecedor de Toalhas Callore" width="200">
        </figure>
        <div class="text">
            <h2>Qualidade</h2>
            <p>Nossos aquecedores são de alta qualidade, pois utilizamos o tubo industrial SAE 1008 fina frio feito de aço
                carbono de baixo teor de carbono. Esse material possui resistência à corrosão, tornando-o adequado para
                ambientes desafiadores, além de proporcionar uma longa vida útil aos aquecedores.</p>
            <p>Além disso, aplicamos uma pintura eletrostática nos aquecedores de toalhas Callore. Essa técnica de
                acabamento oferece uma série de benefícios, como durabilidade, resistência à umidade, cores vibrantes,
                resistência a produtos químicos e um processo ecologicamente amigável. Todos esses benefícios combinados
                resultam em um aquecedor de toalhas com aspecto estético atraente, maior durabilidade e desempenho eficiente
                ao longo do tempo.</p>
            <p>Dessa forma, garantimos a qualidade dos nossos aquecedores, proporcionando aos nossos clientes produtos
                duráveis, esteticamente agradáveis e eficientes.</p>
            <p><a href="{{ route('products.list') }}" class="bt-primary-one">Confira os modelos</a></p>
        </div>
    </section>

    <section class="two-line">
        <div class="text">
            <h2>Consumo de energia</h2>
            <p>A economia gerada ao optar por um aquecedor de toalhas de 150 watts em vez de uma máquina de secar roupas
                pode ser significativa. A máquina de secar roupas consome consideravelmente mais energia, o que resulta em
                uma conta de eletricidade mais alta ao longo do tempo.</p>
            <p>Por exemplo, se a máquina de secar roupas consome em média 4 kWh por ciclo de secagem e é utilizada 10 vezes
                por mês, e o aquecedor de toalhas consome 0,15 kWh e é utilizado constantemente durante o mês, podemos
                calcular a economia mensal da seguinte forma:</p>

                <ul>
                    <li>Economia mensal = (Consumo da máquina de secar roupas - Consumo do aquecedor de toalhas) * Número de ciclos
                        de secagem mensais</li>
                        <li>Economia mensal = (4 kWh - 0,15 kWh) * 10</li>
                        <li>Economia mensal = 3,85 kWh * 10</li>
                        <li>Economia mensal = 38,5 kWh</li>
                </ul>
            <p>Nesse exemplo, ao utilizar o aquecedor de toalhas em vez da máquina de secar roupas, <strong>seria possível
                    economizar 38,5 kWh de energia elétrica por mês</strong>.</p>
            <p>Para calcular a economia em termos financeiros, considerando a tarifa de energia de R$ 4,16 reais por
                quilowatt-hora (tarifa do RS em 11/2022 bandeira v pt1 alta demanda), podemos multiplicar a economia de
                energia pelo valor da tarifa.</p>
            <p>Economia mensal em reais = Economia total (em quilowatt-hora) * Tarifa de energia (em reais/quilowatt-hora).
            </p>
            <p>Supondo que a economia total seja de 38,5 quilowatt-hora, ao utilizar o aquecedor de toalhas em vez da
                máquina de secar roupas, seria possível economizar aproximadamente <strong>R$ 159,76 por mês</strong> em
                termos de custos com energia elétrica, considerando a tarifa de R$ 4,16 por quilowatt-hora.</p>
            <p>O Aquecedor de Toalhas Callore pode ser comparado ao consumo de energia de uma lâmpada incandescente comum.</p>
            <p><a href="{{ route('products.list') }}" class="bt-primary-one">Confira os modelos</a></p>
        </div>
        <div class="image">
            <figure>
                <img src="{{ asset('img/consumo-energia-aquecedores-de-toalhas-callore.webp') }}"
                    alt="Consumo de energia Aquecedor de Toalhas Callore" width="200">
            </figure>
            <figure>
                <img src="{{ asset('img/consumo-energia-lavagens-aquecedores-de-toalhas-callore.webp') }}"
                    alt="Consumo de energia lavages Aquecedor de Toalhas Callore" width="200">
            </figure>
        </div>
    </section>

    <section class="one-line">
        <figure>
            <img src="{{ asset('img/segurança-aquecedores-de-toalhas-callore.webp') }}"
                alt="Segurança Aquecedor de Toalhas Callore" width="200">
        </figure>
        <div class="text">
            <h2>Segurança</h2>
            <p>Todas as potências dos nossos modelos de aquecedores são especificadas para uma temperatura, de 35ºC a 55 ºC,
                não havendo possibilidade de provocar queimaduras em pessoas, nossos componentes elétricos são isolados,
                quando instalado corretamente, não existe possibilidade de choque elétrico.</p>
            <p>Além disso, nossos produtos possuem o selo do <strong>INMETRO</strong>, Dessa forma, você pode ter a
                tranquilidade de utilizar o nosso produto com confiança, sabendo que foi testado e certificado de acordo com
                as normas vigentes. A segurança é uma prioridade para nós e estamos comprometidos em fornecer produtos
                confiáveis e de alta qualidade.</p>
            <p><a href="{{ route('products.list') }}" class="bt-primary-one">Confira os modelos</a></p>
        </div>
    </section>
    <div class="final-space"></div>
@endsection
