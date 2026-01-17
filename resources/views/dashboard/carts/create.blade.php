@extends('templates.dashboard.main')
@section('content')
    <div class="home-dashboard">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Painel</a>
            <span>></span>
            <a href="{{ route('carts.index') }}">Carrinhos</a>
        </div>

        @include('flash-message')
        
        <h3 class="title">Novo</h3>

        <form action="{{ route('carts.store') }}" method="POST">
            @csrf

            <label>Comprador</label>
            <div class="input">
                <input type="text" name="user" placeholder="1" value="{{ old('user') }}">
                @error('user')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Produtos</label>
            <div class="input">
                <textarea name="products" placeholder="...">{{ old('products') }}</textarea>
                @error('products')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Quantidade total de itens</label>
            <div class="input">
                <input type="number" name="qtd" placeholder="4" value="{{ old('qtd') }}">
                @error('qtd')
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

            <div class="submit">
                <button class="bt-primary-one">Adicionar</button>
            </div>
        </form>
    </div>
@endsection
