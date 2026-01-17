@extends('templates.main')
@section('content')
    <div class="single-message">
        @include('flash-message')
    </div>
    <section class="cart">
        <h2>Seus pedidos</h2>

        <div class="list-orders">
            @if (count($orders))
                <div class="titles">
                    <div class="id">Id</div>
                    <div class="amount">Valor total</div>
                    <div class="status">Status</div>
                    <div class="created_at">Criado em</div>
                    <div class="actions">Ações</div>
                </div>
                @for ($i = 0; $i < count($orders); $i++)
                    <div class="line {{ $i % 2 ? 'pair' : 'odd' }}">
                        <div class="id">{{ $orders[$i]->id }}</div>
                        <div class="amount">R$ {{ number_format($orders[$i]->amount, 2, ',', '.') }}</div>
                        <div class="status">{{ __('status.' . $orders[$i]->status) }}</div>
                        <div class="created_at">{{ \Carbon\Carbon::parse($orders[$i]->created_at)->format('d/m/Y H:i') }}</div>
                        <div class="actions">
                            <a href="{{ route('orders.show_order', $orders[$i]->id) }}" class="bt-primary-one">Ver</a>
                        </div>
                    </div>
                @endfor
            @else
                <div>Nenhuma ordem para seu usuário. Começe a comprar <a href="{{ route('home') }}">clicando aqui</a></div>
            @endif
        </div>
    </section>
@endsection
