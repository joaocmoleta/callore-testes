@extends('templates.dashboard.main')
@section('content')
    <div class="lixeira">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Painel</a>
            <span>></span>
            <a href="{{ route('dashboard-posts') }}">Publicações</a>
        </div>

        <h2 class="title">Publicações deletadas (Lixeira)</h2>
        @include('flash-message')

        <div class="posts-list">
            @if (count($posts) > 0)
                @foreach ($posts as $item)
                    @include('components.item-post', [
                        'item_title' => $item->title,
                        'item_id' => $item->id,
                        'thumbnail' => json_decode($item->thumbnail)[0],
                        'date' => $item->created_at,
                        'status' => $item->status,
                        'options' => 'restaurar',
                    ])
                @endforeach
            @else
                <div class="item">
                    <p>Nenhum item nesta lista.</p>
                </div>
            @endif
            {{ $posts->links('templates.pagination') }}
        </div>
        <script>
            function delPer(ev) {
                let res = confirm('Deseja elmininar o conteúdo? Essa ação é irreverssível.')
                if (!res) {
                    ev.preventDefault()
                }
            }
        </script>
    </div>
@endsection
