@extends('templates.dashboard.main')
@section('content')
    <div class="home-dashboard">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Painel</a>
            <span>></span>
            <a href="{{ route('roles.index') }}">Papéis</a>
        </div>

        @include('flash-message')
        
        <h3 class="title">Novo</h3>

        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="input">
                <input type="text" name="name" placeholder="admin">
                @error('name')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="submit">
                <button class="bt-primary-one">Adicionar</button>
            </div>
        </form>
    </div>
@endsection
