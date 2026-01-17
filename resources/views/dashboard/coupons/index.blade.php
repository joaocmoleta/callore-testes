@extends('templates.dashboard.main')
@section('content')
    <section class="dashboard-main">
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Painel</a>
            <span>></span>
            <span>Listando cupons</span>
        </div>
        <div class="submit">
            <a href="{{ route('coupons.create') }}" class="bt-primary-one">+ Adicionar</a>
        </div>

        @include('flash-message')

        <h3 class="title-admin">Cupons</h3>
        <div class="list-cupons">
            @foreach ($coupons as $item)
                <div class="item {{ $item->id % 2 == 0 ? 'pair' : 'odd' }} {{ $item->deleted_at ? 'deleted' : '' }}">
                    @if ($item->deleted_at)
                        <div class="deleted-alert">Deletado</div>
                    @endif
                    <div>
                        <span><strong>Descrição:</strong> {{ $item->name }}</span>
                    </div>
                    <div class="name">
                        <span><strong>Código:</strong> {{ $item->code }}</span>
                    </div>
                    <div class="name">
                        <span><strong>Desconto:</strong>
                            {{ $item->discount_type == 2 ? 'R$ ' : '' }}{{ $item->discount }}{{ $item->discount_type == 1 ? '%' : '' }}</span>
                    </div>
                    <div class="name">
                        <span><strong>Tipo</strong>:
                            @switch($item->discount_type)
                                @case(1)
                                    Porcentagem por carrinho
                                @break

                                @case(2)
                                    Decimal por carrinho
                                @break

                                @case(3)
                                    Porcentagem por produto
                                @break

                                @case(4)
                                    Decimal por produto
                                @break
                            @endswitch
                        </span>
                    </div>
                    <div class="name">
                        <span><strong>Cupons disponíveis:</strong>
                            {{ $item->valid == -1 ? 'Ilimitados' : $item->valid }}</span>
                    </div>
                    @if ($item->product)
                        <div class="name">
                            <span><strong>Produto:</strong>
                                {{ $products[$item->product] }}</span>
                        </div>
                    @endif
                    <div class="name">
                        <span><strong>Validade:</strong>
                            {{ $item->limit ? \Carbon\Carbon::parse($item->limit)->format('d/m/Y H:i') : 'Indeterminado' }}</span>
                    </div>
                    @if ($item->pay_restrict)
                    <div class="name">
                        <span><strong>Exclusivo para pagamento através de:</strong> {{ __('geral.' . $item->pay_restrict) }}</span>
                    </div>
                    @endif
                    <div class="actions">
                        @if ($item->deleted_at)
                            <form action="{{ route('coupons.restore', $item) }}" method="post">
                                @method('PUT')
                                @csrf
                                <button class="bt-primary-one-small">Restaurar</button>
                            </form>
                        @else
                            <a href="{{ route('coupons.edit', $item) }}" class="bt-primary-one-small">Editar</a>
                            <form action="{{ route('coupons.destroy', $item) }}" method="post">
                                @method('DELETE')
                                @csrf
                                <button class="bt-primary-danger-small">Delete</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
            </div>
        {{ $coupons->links('templates.pagination') }}
    </section>
@endsection
