@extends('templates.dashboard.main')
@section('content')
    <section class="dashboard-main">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Painel</a>
        </div>
        <div>
            <a href="{{ route('roles.create') }}" class="bt-primary-one">+ Adicionar</a>
        </div>
        
        @include('flash-message')

        <h3 class="title-admin">Papéis</h3>
        <div class="list-roles">
            @foreach ($roles as $item)
                <div class="item {{ $item->id % 2 == 0 ? 'pair' : 'odd' }}">
                    <div class="name">
                        <a href="{{ route('roles.edit', $item) }}" class="bt-thirdary-one">{{ $item->name }}</a>
                    </div>
                    <div class="actions">
                        <a href="{{ route('roles.edit', $item) }}" class="bt-primary-one-small">Editar</a>
                        <form action="{{ route('roles.destroy', $item) }}" method="post">
                            @method('DELETE')
                            @csrf
                            <button class="bt-primary-danger-small">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
