@extends('templates.dashboard.main')
@section('content')
    <section class="dashboard-main">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Painel</a>
            <span>></span>
            <span>Listando produtos</span>
        </div>
        <div class="submit">
            <a href="{{ route('products.create') }}" class="bt-primary-one">+ Adicionar</a>
        </div>

        @include('flash-message')

        <h3 class="title-admin">Produtos</h3>

        <div class="list-products-dashboard">
            <div class="titles">
                <div></div>
                <div>Id</div>
                <div>Produto</div>
                <div>Ações</div>
            </div>
            @foreach ($products as $item)
                <div class="item{{ $item->deleted_at ? ' deleted' : '' }}">
                    <div class="thumbnail">
                        <span><a href="{{ route('products.edit', $item) }}"><img src="{{ json_decode($item->images, 0)[0] }}" alt=""></a></span>
                    </div>
                    <div class="name">
                        <span>{{ $item->id }}</span>
                    </div>
                    <div class="name">
                        @if ($item->deleted_at)
                            <span>{{ $item->title }}</span>
                        @else
                            <a href="{{ route('products.edit', $item) }}" class="bt-thirdary-one">{{ $item->title }}</a>
                        @endif
                    </div>
                    <div class="actions">
                        @if ($item->deleted_at)
                            <form action="{{ route('products.restore', $item) }}" method="post">
                                @method('PUT')
                                @csrf
                                <button class="bt-primary-one-small">Restaurar</button>
                            </form>
                        @else
                            <form action="{{ route('products.destroy', $item) }}" method="post">
                                @method('DELETE')
                                @csrf
                                <button class="bt-primary-danger-small">Mover para lixeira</button>
                            </form>
                        @endif

                        <form action="{{ route('products.delete-trash', $item) }}" method="POST"
                            onsubmit="confirmDelete(this, event)">
                            @method('DELETE')
                            @csrf
                            <button class="bt-primary-danger-small">Deletar permanentemente</button>
                        </form>
                    </div>
                </div>
            @endforeach
            {{ $products->links('templates.pagination') }}
        </div>

    </section>
    <script>
        function confirmDelete(e, ev) {
            ev.preventDefault()
            if (confirm('Esta ação não poderá ser desfeita. Deseja continuar?')) {
                e.submit()
            }
        }
    </script>
@endsection
