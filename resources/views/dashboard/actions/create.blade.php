@extends('templates.dashboard.main')
@section('content')
    <div class="home-dashboard">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Painel</a>
            <span>></span>
            <a href="{{ route('actions.index') }}">Ações</a>
        </div>

        @include('flash-message')
        
        <h3 class="title">Novo</h3>

        <form action="{{ route('actions.store') }}" method="POST">
            @csrf

            <label>Nome do grupo</label>
            <div class="input">
                <input type="text" name="group_name" placeholder="Conquistar cliente" value="{{ old('group_name') }}">
                @error('group_name')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Ação</label>
            <div class="input">
                <textarea name="action" placeholder="Verificar como foi a compra">{{ old('action') }}</textarea>
                @error('action')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <label>Pontos</label>
            <div class="input">
                <input type="text" name="points" placeholder="100" value="{{ old('points') }}">
                @error('points')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="submit">
                <button class="bt-primary-one">Adicionar</button>
            </div>
        </form>
    </div>
@endsection
