@extends('templates.dashboard.main')
@section('content')
    <div class="home-dashboard">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Painel</a>
            <span>></span>
            <a href="{{ route('orders.index') }}">Pedidos</a>
        </div>

        @include('flash-message')
        
        <h3 class="title">Novo</h3>

        <form action="{{ route('orders.store') }}" method="POST">
            @csrf

            <label>Produtos</label>
            <div class="input">
                <textarea name="products" placeholder="...">{{ old('products') }}</textarea>
                @error('products')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Comprador</label>
            <div class="input">
                <input type="text" name="user" placeholder="1" value="{{ old('user') }}">
                @error('user')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Status</label>
            <div class="input">
                <input type="text" name="status" placeholder="Aguardando pagamento" value="{{ old('status') }}">
                @error('status')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Total da compra</label>
            <div class="input">
                <input type="text" name="amount" placeholder="1.233,50" value="{{ old('amount') }}">
                @error('amount')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Cupom aplicado</label>
            <div class="input">
                <input type="text" name="coupon" placeholder="INVERNO23" value="{{ old('coupon') }}">
                @error('coupon')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="submit">
                <button class="bt-primary-one">Adicionar</button>
            </div>
        </form>
    </div>
@endsection
