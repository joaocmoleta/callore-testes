@extends('templates.dashboard.main')
@section('content')
    <section class="dashboard-main">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Painel</a>
        </div>

        @include('flash-message')

        <h3 class="title-admin">LEADs</h3>
        @if (count($users))
            <div class="list-leads-dashboard">
                <div class="titles">
                    <div>LEAD</div>
                    <div>Ações pendentes</div>
                    <div>Pontos</div>
                    <div>Ordens emitidas</div>
                    <div>Ações</div>
                </div>
                @foreach ($users as $item)
                    <div class="line{{ $item->deleted_at ? ' deleted' : '' }}">
                        <div class="item">{{ $item->name }}</div>
                        <div class="item">{{ $actions_count[$item->id] }}</div>
                        <div class="item"></div>
                        <div class="item">{{ $purchases_count[$item->id] }}</div>
                        <div class="actions">
                                <a href="{{ route('leads.manager', $item->id)}}" class="bt-primary-one-small">Gerenciar</a>
                        </div>
                    </div>
                @endforeach
                {{ $users->links('templates.pagination') }}
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
