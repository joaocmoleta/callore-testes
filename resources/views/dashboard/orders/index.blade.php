@extends('templates.dashboard.main')
@section('content')
    <section class="dashboard-main">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Painel</a>
            <span>></span>
            <span>Listando os pedidos</span>
        </div>

        @include('flash-message')

        <h3 class="title-admin">Pedidos</h3>
        @if (count($orders))
            <div class="list-orders-dashboard">
                <div class="titles">
                    <div>Id</div>
                    <div>Comprador</div>
                    <div>Status</div>
                    <div>Total</div>
                    <div>Criado</div>
                    <div>Ações</div>
                </div>
                @foreach ($orders as $item)
                    <div class="line{{ $item->deleted_at ? ' deleted' : '' }}">
                        <div class="name">
                            @if ($item->deleted_at)
                                <span>{{ $item->id }}</span>
                            @else
                                <a href="{{ route('orders.edit', $item) }}" class="bt-thirdary-one">{{ $item->id }}</a>
                            @endif
                        </div>
                        <div class="name">
                            <span>{{ $item->name }}</span>
                        </div>
                        <div class="name">
                            <span>{{ __('status-administrativo.' . $item->status) }}</span>
                        </div>
                        <div class="name">
                            <span>R$ {{ number_format($item->amount, 2, ',', '.') }}</span>
                        </div>
                        <div class="name">
                            <span>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="actions">
                            @if ($item->deleted_at)
                                <form action="{{ route('orders.restore', $item) }}" method="post">
                                    @method('PUT')
                                    @csrf
                                    <button class="bt-primary-one-small">Restaurar</button>
                                </form>
                            @else
                                <a href="{{ route('orders.edit', $item) }}" class="bt-primary-one-small">Gerenciar</a>
                                <form action="{{ route('orders.destroy', $item) }}" method="post">
                                    @method('DELETE')
                                    @csrf
                                    <button class="bt-primary-danger-small">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
                {{ $orders->links('templates.pagination') }}
            </div>
        @else
            <div>Nenhum pedido adicionado ainda.</div>
        @endif

    </section>
    <script>
        function confirmPay(ev) {
            if (!confirm('Deseja marcar o pedido como pago?')) {
                ev.preventDefault()
            }
        }
    </script>
@endsection
