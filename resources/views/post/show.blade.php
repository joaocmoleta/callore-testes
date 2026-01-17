@php
    $tagss = '';
@endphp
@for ($i = 0; $i < count($tags); $i++)
    @if ($i > 0)
        @php
            $tagss .= ', ' . $tags[$i]->title;
        @endphp
    @else
        @php
            $tagss = $tagss . $tags[$i]->title;
        @endphp
    @endif
@endfor
@extends('templates.main', ['title' => $post->title, 'description' => strip_tags($post->abstract), 'tagss' => $tagss])
@section('content')
    <div class="single-breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>></span>
        <a href="{{ route('posts') }}">Blog</a>
        <span>></span>
        <span>{{ $post->title }}</span>
    </div>
    <div class="single-post">
        <div class="single-content">
            <h2 class="title">{{ $post->title }}</h2>
            <div class="single-abstract">
                {!! $post->abstract !!}
            </div>
            <div class="single-infs">
                <p>Postado {{ Carbon\Carbon::parse($post->created_at)->locale('pt_BR')->dayName }}
                    {{ Carbon\Carbon::parse($post->created_at)->format('d/m/Y') }} por <span>{{ $author->title }}</span>
                </p>
                <p>
                    @foreach ($categories as $cat)
                        <a class="cat"
                            href="{{ route('posts.category', json_decode($cat)->slug) }}">{{ json_decode($cat)->title }}</a>
                    @endforeach
                </p>
            </div>
            <div class="posts-single">
                <div class="single-thumbnail">
                    <img src="{{ json_decode($post->thumbnail)[0] }}" alt="{{ json_decode($post->thumbnail)[1] }}">
                    <div class="single-thumbnail-legend">{{ json_decode($post->thumbnail)[1] }}</div>
                </div>
                <div class="text">{!! $post->body !!}</div>
            </div>
            <div class="single-tags">
                @foreach ($tags as $tag)
                    <a href="{{ route('posts.tag', json_decode($tag)->slug) }}"
                        class="item">{{ json_decode($tag)->title }}</a>
                @endforeach
            </div>
            <nav class="single-share">
                <p><strong>Compartilhe este artigo: </strong></p>
                <ul>
                    <li>
                        <a href="https://t.me/share/url?&text=<?= strip_tags($post['abstract']) ?>&url={{ Request::url() }}"
                            target="_blank">
                            <img src="{{ asset('icons/telegram.svg') }}" alt="Telegram" width="40">
                        </a>
                    </li>
                    <li>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ Request::url() }}" target="_blank">
                            <img src="{{ asset('icons/linkedin.svg') }}" alt="Linkdedin" width="40">
                        </a>
                    </li>
                    <li>
                        <a href="https://twitter.com/intent/tweet?=&text=<?= strip_tags($post['abstract']) ?>&via={{ Request::url() }}"
                            target="_blank">
                            <img src="{{ asset('icons/twitter.svg') }}" alt="Twitter" width="40">
                        </a>
                    </li>
                    <li>
                        <a href="https://www.facebook.com/sharer.php?u={{ Request::url() }}" target="_blank">
                            <img src="{{ asset('icons/facebook.svg') }}" alt="Facebook" width="40">
                        </a>
                    </li>
                    <li>
                        <span onClick="copyToClipboard(this)" class="copy">Copiar
                            URL</span>
                    </li>
                    <script>
                        let url = '{{ Request::url() }}'
                        let text = `👉 Gostaria de compartilhar com você este conteúdo do blog da Callore! Acesse: ${url}`

                        async function copyToClipboard() {
                            try {
                                await navigator.clipboard.writeText(text);
                            } catch (err) {
                                console.error('Failed to copy text: ', err);
                            }
                        }
                    </script>
                </ul>
            </nav>
            @if (true)
                <div class="similiar-box">
                    <h2>Outros artigos que poderá gostar:</h2>
                    <div class="similiar">
                        @foreach ($similiar as $item)
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
                    </div>
                </div>
            @endif
        </div>
        {{-- <x-sidebar /> --}}
    </div>
@endsection
