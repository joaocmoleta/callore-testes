@extends('templates.main', ['og_title' => 'Pagamento PIX | Aquecedor de Toalhas Callore', 'nofollow' => true, 'noindex' => true])
@section('content')
    <div class="single-message">
        @include('flash-message')
    </div>
    <section class="cart">
        <div class="pix-qr-code" style="">
            <h2 style="">Pagamento através de PIX</h2>
            <p class="image-qr-code-pedido"><img src="{{ $pix_data['src'] }}"></p>
            <p>Esta chave expira em <strong>{{ $pix_data['expiration'] }}</strong></p>
            <p><strong>PIX copia e cola:</strong> {{ $pix_data['chave'] }}</p>
            <p>Ler QR Code acima para efetuar o pagamento ou copiar a chave copia e cola</p>
            <p>Aprovação automática. Após realizar o pagamento, aguarde o e-mail de confirmação.</p>
        </div>
    </section>
@endsection
