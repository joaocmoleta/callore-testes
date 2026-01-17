@extends('templates.dashboard.main')
@section('content')
    <div class="breadcrumb">
        <a href="{{ route('dashboard-tags') }}">Autores</a>
        <span>></span>
        <span>Lista</span>
    </div>
    <h3 class="title-admin">Lista</h3>
    @include('flash-message')

    <div class="sub-header">
        <a href="{{ route('dashboard-authors-create') }}" class="bt-primary-one">Adicionar</a>
    </div>
    <div class="authors-list">
        @foreach ($collection as $item)
            <span class="item">
                <a href="{{ route('dashboard-authors-edit', $item->id) }}">
                    <img src="{{ $item->thumbnail }}">
                </a>
                <a href="{{ route('dashboard-authors-edit', $item->id) }}">{{ $item->title }}</a>
                <a href="{{ route('dashboard-authors-destroy', $item->id) }}" class="bt-primary-danger">Deletar</a>
            </span>
        @endforeach
    </div>
@endsection
