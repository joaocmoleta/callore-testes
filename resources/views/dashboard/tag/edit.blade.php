@extends('templates.dashboard.main')
@section('content')
    <div class="breadcrumb">
        <a href="{{ route('dashboard-tags') }}">Categorias</a>
        <span>></span>
        <span>Edição</span>
    </div>
    <h3 class="title-admin">Edição</h3>
    @include('flash-message')

    <div>
        <form action="{{ route('dashboard-tags-update') }}" method="post">
            @csrf
            <input type="hidden" name="id" value="{{ $tag->id }}">
            <label for="title">Nome</label>
            <div class="input">
                <input type="text" name="title" placeholder="Negócios"
                    value="{{ old('title') ? old('title') : $tag->title }}">
                @error('title')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="submit">
                <button class="bt-primary-one">Atualizar</button>
            </div>
        </form>
    </div>
@endsection
