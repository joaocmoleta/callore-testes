@extends('templates.dashboard.main')
@section('content')
    <section class="dashboard-main">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Painel</a>
            <span>></span>
            <a href="{{ route('permissions.index') }}">Permissões</a>
        </div>
        <div>
            <a href="{{ route('permissions.index') }}" class="bt-primary-one">&larr; Voltar para a lista</a>
        </div>
        @include('flash-message')
        
        <h3 class="title-admin">Novo</h3>
        
        <form action="{{ route('permissions.store') }}" method="POST">
            @csrf
            <div class="input">
                <input type="text" name="name" placeholder="read-post" value="{{ old('name') }}">
                @error('name')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="submit">
                <button class="bt-primary-one">Adicionar</button>
            </div>
        </form>
    </section>
@endsection
