@extends('templates.dashboard.main')
@section('content')
    <section class="dashboard-main">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Painel</a>
        </div>
        <div>
            <a href="{{ route('users.create') }}" class="bt-primary-one">+ Adicionar</a>
        </div>
        
        @include('flash-message')

        <h3 class="title-admin">Usuários</h3>
        <div class="list-users">
            @foreach ($users as $item)
                <div class="item">
                    <div class="name">
                        <a href="{{ route('users.edit', $item) }}" class="bt-thirdary-one">{{ $item->name }}</a>
                    </div>
                    <div class="actions">
                        <a href="{{ route('users.edit', $item) }}" class="bt-primary-one-small">Edit</a>
                        <form action="{{ route('users.destroy', $item) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="bt-primary-danger">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
