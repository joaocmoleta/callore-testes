@extends('templates.main', [
    'og_title' => $product->title,
    'description' => 'Procurando ' . $product->title . '? - 12x - frete grátis - econômico - prático',
    'og_url' => route('products.single', $product->slug),
    'og_image' => asset(json_decode($product->images, 0)[0]),
    'product_brand' => 'Callore',
    'product_availability' => 'in stock',
    'product_condition' => 'new',
    'product_price_amount' => number_format($product->value, 2, '.', ''),
    'product_price_currency' => 'BRL',
    'product_retailer_item_id' => 'callore_' . $product->id,
    'product_item_group_id' => 'aquecedores',
])

@section('content')
    @include('templates.md-product', [
        'brand' => 'Callore',
        'name' => $product->title,
        'description' => strip_tags(html_entity_decode($product->abstract)),
        'productID' => $product->id,
        'url' => route('products.single', $product->slug),
        'image' => asset(json_decode($product->images, 0)[0]),
        'price' => number_format($product->value, 2, '.', ''),
    ])

    @include('flash-message')
    <div class="single-breadcrumb">
        <a href="{{ route('products.list') }}">Produtos</a>
        <span>></span>
        <span>Visualização do produto</span>
    </div>
    <section class="single-products">
        <div class="line-one">

            @include('gallery')

            <div class="side-description">
                <h1>{{ $product->title }}</h1>
                <div class="price">
                    @if ($product->discount)
                        <div class="price-non-discount">R$ {{ number_format($product->value, 2, ',', '.') }}</div>
                        <div>
                            <span class="price-main">R$ {{ number_format($product->discount, 2, ',', '.') }}</span>
                            <span
                                class="price-percent-discount">{{ number_format(100 - ($product->discount * 100) / $product->value, 2, ',') }}%
                                OFF</span><br>
                        </div>
                    @else
                        <div class="price-main">R$ {{ number_format($product->value, 2, ',', '.') }}</div>
                    @endif
                    <div class="price-parcel">ou em até 12x</div>
                </div>
                <div class="description">
                    {!! $product->abstract !!}
                </div>

                @if ($tech_spec->voltage != 0)
                    <div class="bt-toggle">
                        <a class="bt-toggle-opt {{ $tech_spec->voltage == '127' ? 'active' : '' }}"
                            href="{{ route('products.single', $other_voltage->slug) }}" class="bt-primary-one">127V</a>
                        <a class="bt-toggle-opt {{ $tech_spec->voltage == '220' ? 'active' : '' }}"
                            href="{{ route('products.single', $other_voltage->slug) }}" class="bt-primary-one">220V</a>
                    </div>
                @endif

                @if ($product->qtd > 0)
                    <form action="{{ route('carts.add', $product) }}" method="post" class="buy">
                        @method('PUT')
                        @csrf

                        <button class="minus" onclick="remove(this, event)">-</button>
                        <input type="text" name="qtd" value="1" class="add">
                        <button class="plus" onclick="add(this, event)">+</button>
                        <button class="bt-primary-one action">Comprar</button>
                        @error('qtd')
                            <div class="msg-error">{{ $message }}</div>
                        @enderror
                    </form>
                @else
                    <div class="buy">
                        <span class="bt-primary-one">Esgotado</span>
                    </div>
                @endif

                <div class="delivery-simulator" id="delivery-simulator">
                    <form action="" method="post" id="delivery-simulator-form" onsubmit="event.preventDefault()">
                        <input type="hidden" name="height" value="{{ $pack_sizes->h }}">
                        <input type="hidden" name="width" value="{{ $pack_sizes->w }}">
                        <input type="hidden" name="length" value="{{ $pack_sizes->l }}">
                        <input type="hidden" name="weight" value="{{ $pack_sizes->we }}">
                        <input type="hidden" name="value"
                            value="{{ number_format($product->discount ?? $product->value, 2, ',', '.') }}">

                        <label>Frete grátis para todo Brasil.</label>
                        <label>Veja o prazo de entrega:</label>
                        <div class="input">
                            <input type="text" name="cep" id="cep" placeholder="Insira seu cep"
                                value="{{ Cookie::get('cep_user') }}">
                            <button class="bt-primary-one float-in-input"
                                onclick="calculateDelivery(this.previousElementSibling.value)">Calcular</button>
                        </div>
                    </form>

                    <script>
                        let route_calculator = '{{ route('delivery-calculator') }}'
                        let csrf = '{{ csrf_token() }}'
                    </script>
                    @if (env('APP_ENV') == 'local')
                        <script src="{{ asset('js/delivery.js') }}"></script>
                    @else
                        <script src="{{ asset('js/delivery.min.js') }}"></script>
                    @endif
                    <script>
                        // Automatic calculate
                        @if (Cookie::has('cep_user'))
                            calculateDelivery(document.getElementById('cep').value)
                        @endif
                    </script>
                </div>
            </div>
        </div>
        <div class="technical-speccification">
            <h2>Descrição técnica</h2>
            <div class="technical-speccification-columns">
                <div class="technical-speccification-col-1">
                    <div class="content-spec">
                        <div class="field">Tipo de produto</div>
                        <div class="prop">{{ $tech_spec->type }}</div>
                    </div>
                    <div class="content-spec">
                        <div class="field">Cor</div>
                        <div class="prop">{{ $tech_spec->color }}</div>
                    </div>
                    <div class="content-spec">
                        <div class="field">Suporte de parede</div>
                        <div class="prop">{{ $tech_spec->suporte_paredes }}</div>
                    </div>
                    <div class="content-spec">
                        <div class="field">Suporte de chão</div>
                        <div class="prop">{{ $tech_spec->suporte_chao }}</div>
                    </div>
                    <div class="content-spec">
                        <div class="field">Indicado para</div>
                        <div class="prop">{{ $tech_spec->indicate }}</div>
                    </div>
                    <div class="content-spec">
                        <div class="field">Linha</div>
                        <div class="prop">{{ $tech_spec->line }}</div>
                    </div>
                    <div class="content-spec">
                        <div class="field">Fabricante</div>
                        <div class="prop">{{ $tech_spec->manufacturer }}</div>
                    </div>
                    <div class="content-spec">
                        <div class="field">Garantia</div>
                        <div class="prop">{{ $tech_spec->guarantee }} meses</div>
                    </div>

                </div>
                <div class="technical-speccification-col-1">
                    <div class="content-spec">
                        <div class="field">Tensão</div>
                        <div class="prop">{{ $tech_spec->voltage }}V</div>
                    </div>
                    <div class="content-spec">
                        <div class="field">Potência</div>
                        <div class="prop">{{ $tech_spec->wattage }}W</div>
                    </div>
                    <div class="content-spec">
                        <div class="field">Cabo</div>
                        <div class="prop">{{ $tech_spec->cable }}</div>
                    </div>
                    <div class="content-spec">
                        <div class="field">Material</div>
                        <div class="prop">{{ $tech_spec->material }}</div>
                    </div>
                    <div class="content-spec">
                        <div class="field">Pintura</div>
                        <div class="prop">{{ $tech_spec->paint }}</div>
                    </div>
                    <div class="content-spec">
                        <div class="field">Dimensões (altura x largura x comprimento)</div>
                        <div class="prop">{{ $sizes->h }} x {{ $sizes->w }} x {{ $sizes->l }}cm </div>
                    </div>
                    <div class="content-spec">
                        <div class="field">Peso da embalagem</div>
                        <div class="prop">{{ $sizes->we }}kg </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="description">
            <h2>Descrição</h2>
            {!! $product->description !!}
        </div>

    </section>

    <section class="similar-single-box">
        <h2 class="similars-title">Quem viu este produto também comprou</h2>
        @include('similiars-pc')

        <script src="{{ asset('js/add-remove-qtd.min.js') }}"></script>

        <script>
            configurations_carousel.push({
                id: 200,
                htmlObj: '#similiars',
                automatic: true,
                duration: 5000,
                type: 'multi',
                nav: true,
                bubbles: true
            }, {
                id: 100,
                htmlObj: '#gallery',
                automatic: false,
                duration: 5000,
                type: 'simple',
                nav: true,
                bubbles: false
            })
        </script>
    </section>
@endsection
