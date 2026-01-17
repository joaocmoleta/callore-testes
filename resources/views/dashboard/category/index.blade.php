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
            <span>Lista</span>
        </div>

        <h2>Lista</h2>
        
        @include('flash-message')

        <div class="sub-header">
            <a href="{{ route('dashboard-categories-create') }}" class="bt-primary-one">Adicionar</a>
        </div>

        <div class="content-list">
            @foreach ($collection as $item)
                <span class="item">
                    <a href="{{ route('dashboard-categories-edit', $item->id) }}">{{ $item->title }}</a>
                    <a href="{{ route('dashboard-categories-destroy', $item->id) }}"
                        class="bt-primary-danger-small" onclick="confirmar(this, event)">x</a>
                </span>
            @endforeach

            <script>
                function confirmar(e, ev) {
                    if(!confirm('Essa ação é irreversível. Tem certeza que deseja remover a categoria?')) {
                        ev.preventDefault()
                    }
                }
            </script>
        </div>
    </div>
@endsection
