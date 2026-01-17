@extends('templates.dashboard.main')
@section('content')
    <div class="home-dashboard">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Painel</a>
            <span>></span>
            <a href="{{ route('leads.index') }}">Ações</a>
        </div>

        @include('flash-message')

        <h3 class="title">Novo</h3>

        <form action="{{ route('leads.store') }}" method="POST">
            @csrf

            <label>Usuário</label>
            <div class="input">
                <select name="user" id="">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ old('user') == $user->id ? 'select' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('user')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Pontos (quanto maior, melhor o relacionamento com o cliente)</label>
            <div class="input">
                <input type="text" name="points" placeholder="100" value="{{ old('points') }}">
                @error('points')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Compras</label>
            <div class="input">
                <input type="text" name="purchases" value="{{ old('purchases') }}">
                @error('purchases')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="submit">
                <button class="bt-primary-one">Adicionar</button>
            </div>
        </form>
    </div>
@endsection
