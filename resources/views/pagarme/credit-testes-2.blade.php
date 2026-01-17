{{-- // Cartão de crédito pagarme testes criptografa cartão --}}

<div style="" class="pagamento-cartao-testes">
    <form action="{{ route('pay.credit_card_pagarme') }}" method="post">
        @csrf

        <div class="input">
            <input type="text">
        </div>
    </form>
</div>
@if (env('APP_ENV') == 'production')
    <link rel="stylesheet" href="{{ asset('css/styles.min.css?v=4.4') }}">
@else
    <link rel="stylesheet" href="{{ asset('css/styles.css?v=4.4') }}">
@endif
