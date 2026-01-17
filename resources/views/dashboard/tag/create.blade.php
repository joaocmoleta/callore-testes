@extends('templates.dashboard.main')
@section('content')
    <div class="breadcrumb">
        <a href="{{ route('dashboard-tags') }}">Categorias</a>
        <span>></span>
        <span>Nova</span>
    </div>
    <h3 class="title-admin">Nova</h3>
    @include('flash-message')

    <div>
        <form action="{{ route('dashboard-tags-store') }}" method="post">
            @csrf
            <label for="title">Nome</label>
            <div class="input">
                <input type="text" name="title" placeholder="Tecnologia" value="{{ old('title') }}">
                @error('title')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="submit">
                <button class="bt-primary-one">Adicionar</button>
            </div>
        </form>
    </div>
@endsection
