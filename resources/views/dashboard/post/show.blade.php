@extends('templates.dashboard.main')
@section('content')
    <a href="{{ route('dashboard-posts') }}">
        <h2 class="title">Publicações</h2>
    </a>
    <div class="sub-header">
        <a href="{{ route('dashboard-posts') }}" class="bt-thirdary-one">Cancelar</a>
    </div>
    <div>
        <form action="{{ route('dashboard-posts-update') }}" method="post">
            @csrf
            <input type="hidden" name="id" value="{{ $post->id }}">
            <label for="title">Título</label>
            <div class="input">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path
                        d="M17 18.597v.403h-10v-.417c-.004-1.112.044-1.747 1.324-2.043 1.402-.324 2.787-.613 2.121-1.841-1.972-3.637-.562-5.699 1.555-5.699 2.077 0 3.521 1.985 1.556 5.699-.647 1.22.688 1.51 2.121 1.841 1.284.297 1.328.936 1.323 2.057zm-1-14.597v2h3v16h-14v-16h3v-2h-5v20h18v-20h-5zm-6-4v6h4v-6h-4zm2 4c-.553 0-1-.448-1-1s.447-1 1-1 1 .448 1 1-.447 1-1 1z" />
                </svg>
                <input type="text" name="title" placeholder="Entrevista com o especialista Dr. Fulano da Silva" value="{{ old('title') ? old('title') : $post->title }}">
                @error('title')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>
            <label for="abstract">Resumo</label>
            <div class="input">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path
                        d="M17 18.597v.403h-10v-.417c-.004-1.112.044-1.747 1.324-2.043 1.402-.324 2.787-.613 2.121-1.841-1.972-3.637-.562-5.699 1.555-5.699 2.077 0 3.521 1.985 1.556 5.699-.647 1.22.688 1.51 2.121 1.841 1.284.297 1.328.936 1.323 2.057zm-1-14.597v2h3v16h-14v-16h3v-2h-5v20h18v-20h-5zm-6-4v6h4v-6h-4zm2 4c-.553 0-1-.448-1-1s.447-1 1-1 1 .448 1 1-.447 1-1 1z" />
                </svg>
                <textarea name="abstract" placeholder="Nesta edição você poderá conferir uma entrevista exclusiva com o Dr. Fulano da Silva">{{ old('abstract') ? old('abstract') : $post->abstract }}</textarea>
                @error('abstract')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>
            <label for="body">Corpo</label>
            <div class="input">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path
                        d="M17 18.597v.403h-10v-.417c-.004-1.112.044-1.747 1.324-2.043 1.402-.324 2.787-.613 2.121-1.841-1.972-3.637-.562-5.699 1.555-5.699 2.077 0 3.521 1.985 1.556 5.699-.647 1.22.688 1.51 2.121 1.841 1.284.297 1.328.936 1.323 2.057zm-1-14.597v2h3v16h-14v-16h3v-2h-5v20h18v-20h-5zm-6-4v6h4v-6h-4zm2 4c-.553 0-1-.448-1-1s.447-1 1-1 1 .448 1 1-.447 1-1 1z" />
                </svg>
                <textarea name="body" placeholder="Matéria na integra com fotos, links, conteúdos, parágrafos, substítulos...">{{ old('body') ? old('body') : $post->body }}</textarea>
                @error('body')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>
            <label for="author">Autor</label>
            <div class="input">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path
                        d="M17 18.597v.403h-10v-.417c-.004-1.112.044-1.747 1.324-2.043 1.402-.324 2.787-.613 2.121-1.841-1.972-3.637-.562-5.699 1.555-5.699 2.077 0 3.521 1.985 1.556 5.699-.647 1.22.688 1.51 2.121 1.841 1.284.297 1.328.936 1.323 2.057zm-1-14.597v2h3v16h-14v-16h3v-2h-5v20h18v-20h-5zm-6-4v6h4v-6h-4zm2 4c-.553 0-1-.448-1-1s.447-1 1-1 1 .448 1 1-.447 1-1 1z" />
                </svg>
                <input type="number" name="author" value="{{ old('author') ? old('author') : $post->author }}">
                @error('author')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>
            <label for="body">Imagem de destaque</label>
            <div class="input">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path
                        d="M17 18.597v.403h-10v-.417c-.004-1.112.044-1.747 1.324-2.043 1.402-.324 2.787-.613 2.121-1.841-1.972-3.637-.562-5.699 1.555-5.699 2.077 0 3.521 1.985 1.556 5.699-.647 1.22.688 1.51 2.121 1.841 1.284.297 1.328.936 1.323 2.057zm-1-14.597v2h3v16h-14v-16h3v-2h-5v20h18v-20h-5zm-6-4v6h4v-6h-4zm2 4c-.553 0-1-.448-1-1s.447-1 1-1 1 .448 1 1-.447 1-1 1z" />
                </svg>
                <input type="text" name="thumbnail" value="{{ old('thumbnail') ? old('thumbnail') : $post->thumbnail }}">
                @error('thumbnail')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>
            <label for="body">Status</label>
            <div class="input">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path
                        d="M17 18.597v.403h-10v-.417c-.004-1.112.044-1.747 1.324-2.043 1.402-.324 2.787-.613 2.121-1.841-1.972-3.637-.562-5.699 1.555-5.699 2.077 0 3.521 1.985 1.556 5.699-.647 1.22.688 1.51 2.121 1.841 1.284.297 1.328.936 1.323 2.057zm-1-14.597v2h3v16h-14v-16h3v-2h-5v20h18v-20h-5zm-6-4v6h4v-6h-4zm2 4c-.553 0-1-.448-1-1s.447-1 1-1 1 .448 1 1-.447 1-1 1z" />
                </svg>
                <input type="text" name="status" value="{{ old('status') ? old('status') : $post->status }}">
                @error('status')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="submit">
                <button class="bt-primary-one">Adicionar</button>
            </div>
        </form>
    </div>
@endsection
