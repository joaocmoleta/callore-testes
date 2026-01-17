@extends('templates.main', [
    'og_title' => $og_title,
    'description' => $description,
])
@section('content')
        <div class="single-breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>></span>
            <a href="{{ route('posts') }}">Blog</a>
            <span>></span>
            <span>Tag</span>
            <span>></span>
            <span>{{ $tag }}</span>
        </div>
        <div class="posts-list-box">
            <h2>{{ $og_title }}</h2>
            <div class="descricao-blog">
                {!! $description !!}
            </div>
            <div class="content-posts-list">
                @foreach ($collection as $item)
                    <div class="item">
                        <div class="thumbnail">
                            <a href="{{ route('posts.show', $item->slug) }}">
                                <img src="{{ json_decode($item->thumbnail)[0] }}"
                                    alt="{{ json_decode($item->thumbnail)[1] }}">
                            </a>
                        </div>
                    <a href="{{ route('posts.show', $item->slug) }}" class="post-title"><h3>{{ $item->title }}</h3></a>
                        <a href="{{ route('posts.show', $item->slug) }}" class="bt-primary-one">Ler</a>
                    </div>
                @endforeach
                {{ $collection->links('templates.pagination') }}
            </div>
            {{-- <x-sidebar /> --}}
        </div>
@endsection
