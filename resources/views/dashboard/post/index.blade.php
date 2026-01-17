@extends('templates.dashboard.main')
@section('content')
    <div class="post-list-box">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Painel</a>
            <span>></span>
            <span>Publicações</span>
        </div>
        <h2>Publicações</h2>
        @include('flash-message')

        <div class="sub-header">
            <a href="{{ route('dashboard-posts-create') }}" class="bt-primary-one">Adicionar</a>
            <a href="{{ route('dashboard-posts-trash') }}" class="bt-primary-one">Lixeira</a>
            <a href="{{ route('dashboard-categories') }}" class="bt-primary-one">Categorias</a>
        </div>
        <div class="search-bar-posts">
            <div class="search-input">
                <img src="{{ asset('icons/search.svg') }}" alt="Buscar produtos">
                @csrf
                <input type="hidden" name="request_search" value="{{ route('search') }}">
                <input type="text" placeholder="Digite o que você procura..." onkeyup="search(this)">
                <div class="dropdown" style="display: none">
                    Buscando resultados
                </div>
            </div>
            <script src="/js/dash-search.js"></script>
        </div>

        <div class="posts-list">
            @foreach ($posts_draft as $item)
                @include('components.item-post', [
                    'item_title' => $item->title,
                    'item_id' => $item->id,
                    'thumbnail' => json_decode($item->thumbnail)[0],
                    'date' => $item->created_at,
                    'status' => $item->status,
                    'options' => 'editar',
                ])
            @endforeach
            {{ $posts_draft->links('templates.pagination') }}
        </div>
        <div class="posts-list">
            @foreach ($collection as $item)
                @include('components.item-post', [
                    'item_title' => $item->title,
                    'item_id' => $item->id,
                    'thumbnail' => json_decode($item->thumbnail)[0],
                    'date' => $item->created_at,
                    'status' => $item->status,
                    'options' => 'editar',
                ])
            @endforeach
            {{ $collection->links('templates.pagination') }}
        </div>
    </div>
@endsection
