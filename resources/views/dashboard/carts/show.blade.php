@extends('templates.dashboard.main')
@section('content')
    <div class="home-dashboard">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Painel</a>
            <span>></span>
            <a href="{{ route('carts.index') }}">Carrinhos</a>
        </div>

        @include('flash-message')

        <h3 class="title">Detalhes</h3>

        <div class="cart-details">
            <p>Carrinho criado em {{ \Carbon\Carbon::parse($cart_and_more->created_at)->format('d/m/y H:i') }}</p>
            <p>Última atualização {{ \Carbon\Carbon::parse($cart_and_more->updated_at)->format('d/m/y H:i') }}</p>
            @if ($cart_and_more->deleted_at)
                <p>Carrinho deletado ou movido para o checkout em:
                    {{ \Carbon\Carbon::parse($cart_and_more->deleted_at)->format('d/m/y H:i') }}</p>
            @endif
        </div>

        <div class="cart-details-geo">
            @if ($cart_and_more->country)
                <p>Acesso vindo de {{ $cart_and_more->district ? $cart_and_more->district . ' -' : '' }}
                    {{ $cart_and_more->city ? $cart_and_more->city . ' -' : '' }}
                    {{ $cart_and_more->region ? $cart_and_more->region . ' -' : '' }} {{ $cart_and_more->country }}
                    {{ $cart_and_more->cep ? 'CEP: ' . $cart_and_more->cep : '' }}</p>
                <p>IP de origem: {{ $cart_and_more->ip }}</p>
            @else
                <p>Atualize a página para carregar novos dados.</p>
            @endif
        </div>

        <div class="cart-details-who">
            <p>Usuário que acessou: {{ $cart_and_more->nome }}</p>
            <p>E-mail: <a href="mailto:{{ $cart_and_more->email }}" target="_blank">{{ $cart_and_more->email }}</a></p>
            <p>Telefone: <a href="tel:{{ $cart_and_more->phone }}"
                    target="_blank">+{{ substr($cart_and_more->phone, 0, 2) }} ({{ substr($cart_and_more->phone, 2, 2) }}) {{ substr($cart_and_more->phone, 4) }}</a></p>
            <p><a href="https://wa.me/+{{ $cart_and_more->phone }}"
                    target="_blank">WhatsApp (se número tiver)</a></p>
        </div>

        <div class="cart-details-products">
            <div class="titles">
                <div class="product_name">Produto</div>
                <div class="value">Valor unitário</div>
                <div class="qtd">Qtd</div>
                <div class="qtd">Subtotal</div>
            </div>
            @foreach ($products as $item)
                <div class="line">
                    <div>{{ $item['title'] }}</div>
                    <div>R$ {{ number_format($item['value_uni'], 2, ',', '.') }}</div>
                    <div>{{ $item['qtd'] }}</div>
                    <div>R$ {{ number_format($item['value_uni'] * $item['qtd'], 2, ',', '.') }}</div>
                </div>
            @endforeach
            <div class="totals">
                <p>Total do carrinho: R$ {{ number_format($cart_and_more->amount, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>
@endsection
