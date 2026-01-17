@extends('templates.dashboard.main')
@section('content')
    <div class="categories-list">
    <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Painel</a>
            <span>></span>
            <a href="{{ route('dashboard-posts') }}">Publicações</a>
            <span>></span>
        <a href="{{ route('dashboard-categories') }}">Categorias</a>
        <span>></span>
        <span>Nova</span>
    </div>

    <h3>Nova</h3>

    @include('flash-message')

    <div>
        <form action="{{ route('dashboard-categories-store') }}" method="post">
            @csrf
            <label for="title">Nome</label>
            <div class="input">
                <input type="text" name="title" placeholder="Negócios" value="{{ old('title') }}">
                @error('title')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="submit">
                <button class="bt-primary-one">Adicionar</button>
            </div>
        </form>
    </div>
    </div>
@endsection
