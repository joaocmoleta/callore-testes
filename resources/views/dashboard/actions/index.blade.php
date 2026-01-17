@extends('templates.dashboard.main')
@section('content')
    <section class="dashboard-main">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Painel</a>
        </div>

        @include('flash-message')

        <h3 class="title-admin">Ações</h3>
        <div>
            <a href="{{ route('actions.create') }}" class="bt-primary-one">Nova</a>
        </div>
        @if (count($actions))
            <div class="list-actions-dashboard">
                <div class="titles">
                    <div>Ação</div>
                    <div>Grupo</div>
                    <div>Pontos</div>
                    <div>Ações</div>
                </div>
                @foreach ($actions as $item)
                    <div class="line{{ $item->deleted_at ? ' deleted' : '' }}">
                        <div class="item">{{ \Illuminate\Support\Str::limit($item->action, 38) }}</div>
                        <div class="item">{{ $item->group_name }}</div>
                        <div class="item">{{ $item->points }}</div>
                        <div class="actions">
                            @if ($item->deleted_at)
                                <form action="{{ route('actions.restore', $item) }}" method="post">
                                    @method('PUT')
                                    @csrf
                                    <button class="bt-primary-one-small">Restaurar</button>
                                </form>
                            @else
                                <a href="{{ route('actions.edit', $item) }}" class="bt-primary-one-small">Editar</a>
                                <form action="{{ route('actions.destroy', $item) }}" method="post" onsubmit="delCheck(event)">
                                    @method('DELETE')
                                    @csrf
                                    <button class="bt-primary-danger-small">Deletar</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
                {{ $actions->links('templates.pagination') }}
            </div>
        @else
        <div>Nenhum ação adicionada ainda.</div>
        @endif

    </section>
    <script>
        function delCheck(ev) {
            if (!confirm('Deseja excluir para sempre o item?')) {
                ev.preventDefault()
            }
        }
    </script>
@endsection
