@extends('templates.main', ['og_title' => 'Seu carrinho Aquecedor de Toalhas Callore', 'nofollow' => true, 'noindex' => true])
@section('content')
    <div class="single-message">
        @include('flash-message')
    </div>
    <section class="cart">
        <h2>Seu carrinho</h2>

        <div class="list-cart-products">
            @if ($cart_list)
                <div class="titles">
                    <div class="id"></div>
                    <div class="product_name">Produto</div>
                    <div class="value">Valor unitário</div>
                    <div class="qtd">Qtd</div>
                    <div class="qtd">Subtotal</div>
                    <div class="actions">Ações</div>
                </div>
                @for ($i = 0; $i < count($cart_list); $i++)
                    <div class="line {{ $i % 2 ? 'pair' : 'odd' }}">
                        <div class="product_name_on_cart">
                            <a href="{{ route('products.single', $cart_list[$i]['slug']) }}">
                                <img src="{{ asset(isset($cart_list[$i]['thumbnail']) ? $cart_list[$i]['thumbnail'] : 'icons/cart.svg') }}"
                                    alt="{{ $cart_list[$i]['name'] }}" width="100">
                            </a>
                            <a
                                href="{{ route('products.single', $cart_list[$i]['slug']) }}">{{ $cart_list[$i]['name'] }}</a>
                        </div>
                        <div class="value">R$ {{ number_format($cart_list[$i]['value_uni'], 2, ',', '.') }}</div>
                        <div class="qtd">
                            <form action="{{ route('carts.reduce', $cart_list[$i]['id']) }}" method="post" class="reduce">
                                @method('PUT')
                                @csrf
                                <input type="hidden" name="id_product" value="{{ $cart_list[$i]['id'] }}">
                                <button class="bt-primary-one-small">-</button>
                            </form>
                            <span> {{ $cart_list[$i]['qtd'] }} </span>
                            <form action="{{ route('carts.plus', $cart_list[$i]['id']) }}" method="post" class="plus">
                                @method('PUT')
                                @csrf
                                <input type="hidden" name="id_product" value="{{ $cart_list[$i]['id'] }}">
                                <button class="bt-primary-one-small">+</button>
                            </form>
                        </div>
                        <div class="qtd">R$ {{ number_format($cart_list[$i]['subtotal'], 2, ',', '.') }}</div>
                        <div class="actions">
                            <form action="{{ route('carts.remove', $cart_list[$i]['id']) }}" method="post" class="buy">
                                @method('PUT')
                                @csrf

                                <button class="bt-primary-danger-small">Excluir</button>
                            </form>
                        </div>
                    </div>
                @endfor
                <div class="total">
                    <div>Total</div>
                    <div>R$ {{ number_format($cart->amount, 2, ',', '.') ?? '' }}</div>
                </div>
                <div class="checkout">
                    <form action="{{ route('orders.add') }}" method="post">
                        {{-- @method('PUT') --}}
                        @csrf
                        <input type="hidden" name="products" value="{{ json_encode($cart_list) }}">
                        <input type="hidden" name="qtd" value="{{ $cart->qtd }}">
                        <input type="hidden" name="amount" value="{{ $cart->amount }}">

                        <div class="submit">
                            <button class="bt-primary-danger">Finalizar compra</button>
                        </div>
                    </form>
                </div>
            @else
                <div class="cart-vazio">
                    <img src="{{ asset('icons/cart-vazio.svg') }}" alt="Nenhum item no carrinho">
                    <p>Ops! Parece que seu carrinho está vazio!</p>
                    <p>Começe adicionando um produto agora</p>
                    <p><a href="{{ route('products.list') }}" class="bt-primary-one">Adicionar</a></p>
                </div>
            @endif

            <div class="alternative-checkout-cart-page">
                <a href="{{ route('orders.list') }}" class="bt-primary-one">Ver meus pedidos</a>
                <a href="{{ route('products.list') }}" class="bt-primary-one" style="margin-bottom: 10px">Continuar
                    comprando</a>
            </div>
        </div>
        <script>
            function reduce_cart(e, id, session) {
                console.log(e.nextElementSibling.innerText);
                document.getElementsByClassName('loader-box-cart')[0].style.display = 'flex'

                fetch('{{ env('APP_URL') }}/api/cart/reduce', {
                        method: 'POST',
                        body: JSON.stringify({
                            id_product: id,
                            session: session
                        }),
                        headers: {
                            "Content-type": "application/json; charset=UTF-8"
                        }
                    })
                    .then(function(response) {
                        // The API call was successful!
                        if (response.ok) {
                            return response.json();
                        } else {
                            return Promise.reject(response);
                        }
                    }).then(function(data) {
                        // This is the JSON from our response
                        console.log(data);
                        document.getElementsByClassName('loader-box-cart')[0].style.display = 'none'
                    }).catch(function(err) {
                        // There was an error
                        console.warn('Something went wrong.', err);
                    });
            }

            function add_cart(e) {
                console.log(e.previousElementSibling.innerText);
                document.getElementsByClassName('loader-box-cart')[0].style.display = 'flex'
            }

            if (typeof gtag !== "undefined") {
                gtag("event", "purchase", {
                    transaction_id: "{{ \Carbon\Carbon::now()->format('YmdHis') }}",
                    value: {{ $cart->amount }},
                    tax: 0,
                    shipping: 0,
                    currency: "BRL",
                    coupon: "{{ $cart->coupon }}",
                    items: [
                        // If someone purchases more than one item, 
                        // you can add those items to the items array
                        @foreach ($cart_list as $item)
                            {
                                item_id: {{ $item['id'] }},
                                item_name: "{{ $item['name'] }}",
                                affiliation: "",
                                coupon: "",
                                discount: 0,
                                index: 0,
                                item_brand: "Callore",
                                item_category: "Eletrônico",
                                item_category2: "Eletrodoméstico",
                                // item_list_id: "related_products",
                                // item_list_name: "Related Products",
                                item_variant: "",
                                location_id: "ChIJHctqVtKcGZURH-mHn6gRMWA", // Porto Alegre, RS, Brasil
                                price: {{ $item['value_uni'] }},
                                quantity: {{ $item['qtd'] }}
                            },
                        @endforeach
                    ]
                });
            }
        </script>
    </section>
@endsection
