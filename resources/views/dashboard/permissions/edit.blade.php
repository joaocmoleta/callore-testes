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
        
        <h3 class="title-admin">Edição</h3>

        <form action="{{ route('permissions.update', $permission) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="input">
                <input type="text" name="name" placeholder="read-post"
                    value="{{ old('name') ? old('name') : $permission->name }}">
                @error('name')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="submit">
                <button class="bt-primary-one">Atualizar</button>
            </div>
        </form>
    </section>
@endsection
