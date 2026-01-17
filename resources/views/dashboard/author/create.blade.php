@extends('templates.dashboard.main')
@section('content')
    <div class="breadcrumb">
        <a href="{{ route('dashboard-authors') }}">Autores</a>
        <span>></span>
        <span>Novo</span>
    </div>
    <h3 class="title-admin">Novo</h3>
    @include('flash-message')

    <div>
        <form action="{{ route('dashboard-authors-store') }}" method="post">
            @csrf
            <label for="title">Nome</label>
            <div class="input">
                <input type="text" name="title" placeholder="Redação" value="{{ old('title') }}">
                @error('title')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>
            <label for="thumbnail">Foto de perfil</label>
            <div class="input">
                <input type="text" name="thumbnail" value="{{ old('thumbnail') }}">
                @error('thumbnail')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>
            <label for="description">Descrição (apresentação)</label>
            <div class="input">
                <textarea name="description" placeholder="Aspirações, carreira, apresentação">{{ old('description') }}</textarea>
                @error('description')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="submit">
                <button class="bt-primary-one">Adicionar</button>
            </div>
        </form>
    </div>
@endsection
