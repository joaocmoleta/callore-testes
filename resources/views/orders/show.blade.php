@extends('templates.main', ['og_title' => 'Pedido | Aquecedor de Toalhas Callore', 'nofollow' => true, 'noindex' => true])
@section('content')
    <div class="single-message">
        @include('flash-message')
    </div>
    <section class="cart">
        <h2>Seu pedido: {{ $db_order->id }}</h2>

        <div class="order-general-info">
            <p><strong>Data da compra:</strong> {{ \Carbon\Carbon::parse($db_order->created_at)->format('d/m/y H:i') }}</p>
            <p><strong>Status do pedido:</strong> {{ __('status.' . $db_order->status) }}</p>
            <p><strong>Total do pedido:</strong> R$ {{ number_format($amount, 2, ',', '.') ?? '' }}</p>

            @if ($db_order->pay_id)
                <p><strong>Método de pagamento:</strong>
                    {{ $db_order->type ? __('geral.' . $db_order->type) : '-' }}</p>
            @endif

            @if ($db_order->type == 'CREDIT_CARD')
                <p><strong>Parcelas:</strong> {{ $db_order->installments }}</p>
                <p><strong>Cartão:</strong> {{ $db_order->cd_brand . ' final ' . $db_order->cd_last_digits }}
                </p>
            @endif
        </div>

        @if (isset($encomenda->volumes))
            @php
                $total_express = json_decode($encomenda->volumes);
            @endphp

            <div class="total-express-edit-order">
                <h3>Entrega Total express</h3>
                <p><strong>Código de rastreio:</strong> {{ $total_express[0]->awb }} <a
                        href="https://tracking.totalexpress.com.br/poupup_track.php?reid={{ env('TOTAL_EXPRESS_ID') }}&pedido=pedido-{{ $db_order->id }}&nfiscal={{ $invoice->numero }}"
                        target="_blank">rastrei no link</a></p>
                <p></p>
            </div>
        @endif

        <div class="">
            @if ($db_order->type == 'Boleto' && $db_order->status == 'waiting_pay_boleto')
                <p><strong>Código de barras:</strong> {{ $db_order->barcode ?? 'Processando' }}</p>
                @if ($links)
                    <p style="margin: 10px 0;"><a href="{{ $links[0]->href }}" target="_blank"
                            class="bt-primary-one">Visualizar/Baixar PDF</a></p>
                    <p>
                        <img src="{{ $links[1]->href }}" alt="Boleto código de barras: {{ $db_order->barcode }}">
                    </p>
                @endif
            @endif
            @if ($db_order->type == 'PIX' && $db_order->status == 'waiting_payment')
                <section class="cart">
                    <div class="pix-qr-code" style="">
                        <h2 style="">Pagamento através de PIX</h2>
                        <p class="image-qr-code-pedido"><img src="{{ $pix_data['src'] }}"></p>
                        <p>Esta chave expira em <strong>{{ $pix_data['expiration'] }}</strong></p>
                        <p><strong>PIX copia e cola:</strong> {{ $pix_data['chave'] }}</p>
                        <p>Ler QR Code acima para efetuar o pagamento ou copiar a chave copia e cola</p>
                        <p>Aprovação automática. Após realizar o pagamento, aguarde o e-mail de confirmação.</p>
                    </div>

                    <div class="submit">
                        <a href="{{ route('orders.change_payment', $db_order->id) }}" class="bt-primary-one">Trocar meio
                            de pagamento</a>
                    </div>
                </section>
            @endif

            @if ($product_list)
                @if (!isset($complete->tax_id) || $db_order->status == 'new')
                    @include('delivery')
                @endif

                @if (
                    $db_order->status == 'waiting_pay' ||
                        $db_order->status == 'declined' ||
                        ($db_order->status == 'waiting_payment' && $db_order->type != 'PIX') ||
                        $db_order->status == 'failed')
                    <div class="pay-methods">
                        <h3>Escolha a melhor forma de pagamento:</h3>

                        {{-- @role('admin|super')
                        @endrole --}}
                        
                        @include('safrapay.pix-link')
                        @include('safrapay.credit-link')
                        
                        {{-- @include('pagarme.credit-link') --}}

                        {{-- @include('pagarme.pix-opt', $db_order) --}}
                        {{-- @include('pagarme.credit') --}}
                        {{-- @include('pagarme.direto') --}}

                        {{-- <script src="/js/checkout.min.js"></script> --}}
                    </div>
                @endif

                @if ($db_order->status == 'paid' || $db_order->status == 'preparing_your_order')
                    <div class="submit">
                        <a href="{{ route('orders.cancel', $db_order->id) }}" onclick="cancel_bt_press(event)"
                            class="bt-primary-danger">Cancelar este pedido</a>
                        <script>
                            function cancel_bt_press(ev) {
                                if (!confirm('Deseja realmente solicitar o cancelamento?')) {
                                    ev.preventDefault()
                                }
                            }
                        </script>
                    </div>
                @endif

                @if (
                    ($status_delivery &&
                        ($db_order->status == 'shipped' ||
                            $db_order->status == 'last_stage_delivery' ||
                            $db_order->status == 'delivered')) ||
                        $db_order->status == 'delivery_updated')
                    <div class="order-rastreio-entrega-manual">
                        <h3>Entrega</h3>
                        <p><strong>Código de rastreio:</strong> {{ $status_delivery->code ?? '-' }}</p>
                        <p><strong>Método de entrega:</strong> {{ $status_delivery->method ?? '-' }}</p>
                        @foreach ($events as $event)
                            @if ($db_order->status == $event->status)
                                <p class="contrast">
                                    <strong>{{ \Carbon\Carbon::parse($event->created_at)->format('d/m/Y H:i') }} -
                                        {{ __('status.' . $event->status) }}</strong>
                                </p>
                            @else
                                <p>{{ \Carbon\Carbon::parse($event->created_at)->format('d/m/Y H:i') }} -
                                    {{ __('status.' . $event->status) }}</p>
                            @endif
                        @endforeach
                        <p>{{ $status_delivery->estimative ? \Carbon\Carbon::parse($status_delivery->estimative)->format('d/m/Y H:i') : '-' }}
                            - Previsão de entrega</p>
                    </div>
                @endif

                @if (
                    $db_order->status == 'waiting_pay' ||
                        $db_order->status == 'new' ||
                        ($db_order->status == 'waiting_payment' && $db_order->type != 'PIX'))
                    <form action="{{ route('orders.add_coupon') }}" method="POST" class="form-coupon">
                        @csrf
                        <input type="hidden" name="order" value="{{ $db_order->id }}">

                        <label>Tem um cupom de desconto?</label>
                        <div class="input input-and-bt-inter">
                            <input type="text" name="coupon" placeholder="D9PKK5E4LGXA1GO"
                                value="{{ $db_order->coupon ?? '' }}">
                            <button class="bt-primary-one bt-inter">{{ $db_order->coupon ? 'Atualizar' : 'Adicionar' }}
                                cupom</button>
                        </div>
                        {{-- <p>(saiba como conseguir o seu <a href="{{ route('cupons') }}">aqui</a>)</p> --}}

                    </form>
                @endif

                @if (true)
                    <div class="products-on-order">
                        <h3>Produtos</h3>
                        <div class="titles">
                            <div class="id">Id</div>
                            <div class="product_name">Produto</div>
                            <div class="value">Valor unitário</div>
                            <div class="qtd">Qtd</div>
                            <div class="qtd">Subtotal</div>
                        </div>
                        @for ($i = 0; $i < count($product_list); $i++)
                            <div class="line {{ $i % 2 ? 'pair' : 'odd' }}">
                                <div class="id">{{ $product_list[$i]['id'] }}</div>
                                <div class="product_name"><a
                                        href="{{ route('products.single', $product_list[$i]['slug']) }}">{{ $product_list[$i]['name'] }}</a>
                                </div>
                                <div class="value">R$ {{ number_format($product_list[$i]['value_uni'], 2, ',', '.') }}
                                </div>
                                <div class="qtd">
                                    <span> {{ $product_list[$i]['qtd'] }} </span>
                                </div>
                                <div class="qtd">R$ {{ number_format($product_list[$i]['subtotal'], 2, ',', '.') }}
                                </div>
                            </div>
                        @endfor
                    </div>
                @endif

                <div class="total">
                    <div class="col-1">
                        @if (isset($amount_complete))
                            @if ($amount_complete['discount'] != 0)
                                <div>Total produtos</div>
                                <div>
                                    Descontos{{ $amount_complete['coupon']->pay_restrict ? ' (exclusivo para pagamentos através de ' . $amount_complete['coupon']->pay_restrict . ')' : '' }}
                                </div>
                                <div>Total
                                    {{ $amount_complete['coupon']->pay_restrict ? ' (exclusivo para pagamentos através de ' . $amount_complete['coupon']->pay_restrict . ')' : '' }}
                                </div>
                            @else
                                <div>Total</div>
                            @endif
                            @if ($db_order->installments)
                                <div>Total em {{ $db_order->installments }}x</div>
                            @endif
                        @else
                            <div>Total</div>
                        @endif

                        @if (isset($payment))
                            <div>Total pago</div>
                        @endif
                    </div>
                    <div class="col-1">
                        @if (isset($amount_complete))
                            <div>R$ {{ number_format($totals->amount, 2, ',', '.') ?? '' }}</div>
                            @if ($amount_complete['discount'] != 0)
                                {{-- Descontos --}}
                                <div>R$ {{ number_format($amount_complete['discount'], 2, ',', '.') ?? '' }}</div>
                                {{-- Total --}}
                                <div>R$ {{ number_format($amount_complete['amount'], 2, ',', '.') ?? '' }}</div>
                            @endif
                            @if ($db_order->installments)
                                <div>R$
                                    {{ number_format($amount_complete['amount'] * (json_decode(env('PAGARME_PARCELAMENTO'), 1)[$db_order->installments] / 100) + $amount_complete['amount'], 2, ',', '.') }}
                                </div>
                            @endif
                        @else
                            <div>R$ {{ number_format($totals->amount, 2, ',', '.') ?? '' }}</div>
                        @endif

                        @if ($payment)
                            <div>R$ {{ number_format($payment->paid_amount / 100, 2, ',', '.') ?? '' }}</div>
                        @endif

                        {{-- <div>{{ $totals->amount * (json_decode(env('PAGARME_PARCELAMENTO'), 1)[$db_order->installments]/100) + $totals->amount }}</div> --}}
                    </div>
                </div>
                <div class="alternative-checkout-page">
                    @if ($db_order->status == 'new' || $db_order->status == 'declined')
                        <a href="{{ route('carts.list') }}" class="bt-primary-one" style="text-align: center">Voltar ao
                            carrinho</a>
                        <a href="{{ route('products.list') }}" class="bt-primary-one">Adicionar mais produtos</a>
                    @endif
                </div>
                <div class="general-actions">
                    <a href="{{ route('orders.list') }}" class="bt-thirdary-one">← Voltar aos pedidos</a>
                    {{-- <p>{{ $db_order->type }} {{ $db_order->status }}</p> --}}
                    @if ($db_order->type == 'Pix' && $db_order->status == 'PreAuthorized')
                        <a href="{{ route('safra.pix', $db_order->id) }}" class="bt-thirdary-one">Ir para o pagamento</a>
                        <a href="{{ route('safra.pix.remove', $db_order->id) }}" class="bt-primary-one">Escolher outra
                            forma de pagamento</a>
                    @endif
                    @if ($db_order->type == 'Credit' && $db_order->status == 'NotAuthorized')
                        <a href="{{ route('safra.credit.remove', $db_order->id) }}" class="bt-primary-one">Escolher outra
                            forma de pagamento</a>
                    @endif
                </div>
            @else
                <div>Nenhum produto adicionado ainda. Adicione um <a href="{{ route('home') }}">clicando aqui</a></div>
            @endif
        </div>
    </section>
@endsection
