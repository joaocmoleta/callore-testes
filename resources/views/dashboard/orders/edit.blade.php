@extends('templates.dashboard.main')
@section('content')
    {{-- <div class="home-dashboard"> --}}
    <div class="edition-order-screen">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Painel</a>
            <span>></span>
            <a href="{{ route('orders.index') }}">Pedidos</a>
            <span>></span>
            <span><strong>Pedido {{ $order->id }}</strong></span>
        </div>

        @include('flash-message')

        <h3 class="title">Acompanhamento administrativo do pedido</h3>

        <form action="{{ route('orders.up_status') }}" method="POST" class="update-status-order">
            @csrf
            <input type="hidden" name="order" value="{{ $order->id }}">

            <p><strong>Status atual:</strong> {{ __('status-administrativo.' . $order->status) }}</p>

            <label>Mudar status pedido</label>
            <div class="input">
                <select name="status">
                    <option value="">Selecione</option>
                    @foreach ($status as $key => $value)
                        <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>
                            {{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <label>Notificar cliente da mudança?</label>
            <div class="input">
                <input type="checkbox" name="notify" checked>
            </div>

            <div class="submit">
                <button class="bt-primary-one">Atualizar</button>
            </div>
        </form>

        <div class="dados-comprador">
            <h3>Comprador</h3>
            <div class="customer-area">
                <div><strong>Nome: </strong>{{ $order->name }}</div>
                <div><strong>Documento(CPF/CNPJ): </strong>{{ $order->tax_id }}</div>
                <div><strong>E-mail: </strong>{{ $order->email }}</div>
                <div><strong>Telefone: </strong>+{{ $order->phone }}</div>
                <div class="whats-link-pc"><strong>Link whats (Se destinatário possuir): </strong><a
                        href="https://web.whatsapp.com/send?phone={{ $order->phone }}">https://web.whatsapp.com/send?phone={{ $order->phone }}</a>
                </div>
                <div class="whats-link-mob"><strong>Link whats (Se destinatário possuir): </strong><a
                        href="https://wa.me/{{ $order->phone }}">https://wa.me/{{ $order->phone }}</a></div>
                <div>
                    <p><strong>Endereço de entrega: </strong>{{ $order->street ?? 'Rua' }}, {{ $order->number ?? 'N⁰' }} -
                        {{ $order->complement }} -
                        {{ $order->locality ?? 'Bairro' }} -
                        {{ $order->city ?? 'Cidade' }}/{{ $order->region_code ?? 'UF' }}
                        -
                        {{ $order->country ?? 'País' }} - CEP: {{ $order->postal_code ?? '00.000-000' }}</p>
                </div>
            </div>
        </div>

        <div class="products-on-order edit">
            <h3>Produtos</h3>
            <div class="titles">
                <div>Id</div>
                <div>Produto</div>
                <div>Qtd</div>
                <div>Unitário</div>
                <div>Subtotal</div>
            </div>
            @for ($i = 0; $i < count($products); $i++)
                <div class="line">
                    <div>{{ $products[$i]->id }}</div>
                    <div><a href="{{ route('products.single', $products[$i]->slug) }}"
                            target="_blank">{{ $products[$i]->name }}</a></div>
                    <div>{{ $products[$i]->qtd }}</div>
                    <div>R$ {{ number_format($products[$i]->value_uni, 2, ',', '.') }}</div>
                    <div>R$ {{ number_format($products[$i]->subtotal, 2, ',', '.') }}</div>
                </div>
            @endfor
        </div>

        <div class="amount-area">
            <strong>Total produtos:</strong> R$ {{ number_format($total_products, 2, ',', '.') }}
            <strong>Desconto:</strong> R$ {{ number_format($discount['discount'], 2, ',', '.') }}
            <strong>Total:</strong> R$ {{ number_format($total_products - $discount['discount'], 2, ',', '.') }}
            {{-- @if ($order->installments)
                <strong>Total em  {{ $order->installments }}x:</strong> R$ {{ number_format(($total_products - $discount['discount']) * (json_decode(env('PAGARME_PARCELAMENTO'), 1)[$order->installments]/100) + ($total_products - $discount['discount']), 2, ',', '.') }}
            @endif --}}
        </div>

        <div class="pay-area">
            <h3>Pagamento</h3>
            <p><strong>Status:</strong> {{ __('status-administrativo.' . $order->status) ?? 'Nenhum' }}</p>
            <p><strong>Cupom aplicado:</strong> {{ $order->coupon ?? 'Nenhum' }}</p>
            <p><strong>Id (interno):</strong> {{ $order->py_id }}</p>
            <p><strong>Id (pagarme):</strong> {{ $order->pay_id }}</p>
            <p><strong>Forma:</strong> {{ $order->type }}</p>
            {{-- <p><strong>Valor pago/envidado:</strong> R$ {{ number_format($order->py_amount/100, 2, ',', '.') }}</p> --}}
            @if ($order->type == 'CREDIT_CARD')
                <p><strong>Parcelas:</strong> {{ $order->installments }}</p>
                <p><strong>Bandeira:</strong> {{ $order->cd_brand }}</p>
                <p><strong>Primeiros dígitos:</strong> {{ $order->cd_first_digits }}</p>
                <p><strong>Últimos dígitos:</strong> {{ $order->cd_last_digits }}</p>
                <p><strong>Vencimento:</strong> {{ $order->cd_exp_month }}/{{ $order->cd_exp_year }}</p>
                <p><strong>Nome:</strong> {{ $order->cd_holder_name }}</p>
            @endif
            @if ($order->type == 'Boleto')
                <p><strong>Documento pagador:</strong> {{ $order->payemnt_tax_id }}</p>
                <p><strong>Endereço financeiro: </strong>{{ $order->payemnt_street ?? 'Rua' }},
                    {{ $order->payemnt_number ?? 'N⁰' }} -
                    {{ $order->payemnt_locality ?? 'Bairro' }} -
                    {{ $order->payemnt_city ?? 'Cidade' }}/{{ $order->payemnt_region_code ?? 'UF' }} -
                    {{ $order->payemnt_country ?? 'País' }} - CEP: {{ $order->payemnt_postal_code ?? '00.000-000' }}</p>
            @endif
        </div>

        @if ($order->file)
            @include('dashboard.orders.invoice-up')
        @else
            @include('dashboard.orders.invoice-create')
        @endif

        @if (isset($encomenda->volumes))
            @php
                $total_express = json_decode($encomenda->volumes);
            @endphp

            <div class="total-express-edit-order">
                <h3>Total express</h3>
                <p><strong>Código de rastreio:</strong> {{ $total_express[0]->awb }} <a
                        href="https://tracking.totalexpress.com.br/poupup_track.php?reid={{ env('TOTAL_EXPRESS_ID') }}&pedido=pedido-{{ $order->id }}&nfiscal={{ $order->numero }}"
                        target="_blank">rastrei no link</a></p>
                <p></p>
            </div>
        @endif

        <div class="entregas-manuais">
            <h3 style="margin: 30px 0 5px">Entregas manuais</h3>
            @if ($order->delivery_id)
                @include('dashboard.orders.delivery-up')
            @else
                @include('dashboard.orders.delivery-create')
            @endif
        </div>

        <div class="events-historic">
            <h3>Histórico de eventos</h3>
            <p>Todas as mudanças que ocorreram no pedido ao longo do tempo</p>
            <p><strong>Evento administrativo</strong>: refere-se a descrição para o vendedor</p>
            <p><strong>Evento cliente</strong>: refere-se a descrição que aparece para o cliente</p>
            <div class="events-historic-container">
                <div class="line-events-head">
                    <span class="id-event">ID</span>
                    <span class="id-event">Agente</span>
                    <span class="date">Data</span>
                    <span class="event">Evento administrativo</span>
                    <span class="event">Evento cliente</span>
                </div>
                @foreach ($order_events as $item)
                    <div class="line-events">
                        <span class="id-event">{{ $item->id }}</span>
                        <span class="agent">{{ $item->name }}</span>
                        <span class="date">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/y H:i:s') }}</span>
                        <span
                            class="event">{{ trans('status-administrativo.' . $item->status) ? __('status-administrativo.' . $item->status) : $item->status }}
                            ({{ $item->status }})
                        </span>
                        <span class="event">{{ __('status.' . $item->status) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection
