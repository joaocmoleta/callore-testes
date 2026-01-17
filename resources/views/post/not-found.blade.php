@extends('templates.main')
@section('content')
    <div class="posts-list-box">
        <h2 class="title">Notícia não encontrada!</h2>
        <h3>Algumas notícias que pode lhe interessar:</h3>
        <div class="posts-list">
            @foreach ($collection as $item)
                <a href="{{ route('posts.show', $item->slug) }}" class="item">
                    <div class="thumbnail">
                        <img src="{{ json_decode($item->thumbnail)[0] }}" alt="{{ json_decode($item->thumbnail)[1] }}" width="150">
                    </div>
                    <div class="info">
                        <h4>{{ $item->title }}</h4>
                        <span class="bt-thirdary-one">Veja na íntegra</span>
                    </div>
                </a>
            @endforeach
            {{ $collection->links('templates.pagination') }}
        </div>
        <x-sidebar />
    </div>
@endsection
