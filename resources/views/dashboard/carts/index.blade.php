@extends('templates.dashboard.main')
@section('content')
    <section class="dashboard-main">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Painel</a>
                        <span>></span>
            <span>Listando carrinhos</span>
        </div>
        <div class="submit">
            <a href="{{ route('carts.create') }}" class="bt-primary-one">+ Adicionar</a>
        </div>

        @include('flash-message')

        <h3 class="title-admin">Carrinhos</h3>
        <div class="list-carts-dashboard">
            <div class="titles">
                <div>Rastreio de acesso</div>
                <div>Usuário</div>
                <div>Última interação</div>
                <div>Ações</div>
            </div>
            @foreach ($carts as $item)
                <div class="item{{ $item->deleted_at ? ' deleted' : '' }}">
                    <div class="name">{{ $item->district }} - {{ $item->city }} - {{ $item->region }} - {{ $item->country }}</div>
                    <div class="name">{{ $item->name }}</div>
                    <div class="name">{{ \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i') }}</div>
                    <div class="actions">
                        <a href="{{ route('carts.show', $item) }}" class="bt-primary-one">Detalhes</a>
                        @if ($item->deleted_at)
                            <form action="{{ route('carts.restore', $item) }}" method="post">
                                @method('PUT')
                                @csrf
                                <button class="bt-primary-one">Restaurar</button>
                            </form>
                        @else
                        <form action="{{ route('carts.destroy', $item) }}" method="post">
                                @method('DELETE')
                                @csrf
                                <button class="bt-primary-danger">Delete</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
            {{ $carts->links('templates.pagination') }}
        </div>
    </section>
@endsection
