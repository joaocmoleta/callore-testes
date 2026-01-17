@extends('templates.main', [
    'og_title' => 'Blog Callore – Conteúdo Inteligente Sobre Casa, Conforto, Estilo de Vida e nosso aquecedor de toalhas',
    'description' => 'Artigos sobre decoração, tendências, cuidados com o lar, solução de problemas
comuns e ideias que facilitam a vida de quem busca praticidade todos os dias.',
])
@section('content')
    <div class="single-breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>></span>
        <span>Blog</span>
    </div>
    <div class="posts-list-box">
        <h2 class="title">Blog Callore – Conteúdo Inteligente Sobre Casa, Conforto, Estilo de Vida e sobre nosso aquecedor
            de toalhas</h2>
        <div class="descricao-blog">
            Artigos sobre decoração, tendências, cuidados com o lar, solução de problemas
            comuns e ideias que facilitam a vida de quem busca praticidade todos os dias.
        </div>
        <div class="content-posts-list">
            @foreach ($collection as $item)
                <div class="item">
                    <div class="thumbnail">
                        <a href="{{ route('posts.show', $item->slug) }}">
                            <img src="{{ json_decode($item->thumbnail)[0] }}" alt="{{ json_decode($item->thumbnail)[1] }}">
                        </a>
                    </div>
                    <a href="{{ route('posts.show', $item->slug) }}" class="post-title"><h3>{{ $item->title }}</h3></a>
                    <a href="{{ route('posts.show', $item->slug) }}" class="bt-primary-one">Ler</a>
                </div>
            @endforeach
            {{ $collection->links('templates.pagination') }}
        </div>
    </div>
@endsection
