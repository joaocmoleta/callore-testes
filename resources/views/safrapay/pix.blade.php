@extends('templates.main', [
    'description' => '',
    'og_title' => 'Seu pedido - Pagamento com Pix',
    'noindex' => true,
])
@section('content')
    <div class="single-message">
        @include('flash-message')
    </div>

    <div class="payment-card-safrapay">
        <h1>Seu pedido - Pagamento com Pix</h1>
        <p><strong>Status do seu pedido:</strong> {{ __('status.' . $finded_order->status) }}</p>
        <p><strong>Valor a pagar:</strong> R$ {{ number_format($amount, 2, ',', '.') }}</p>
        <p>Insira os dados abaixo para gerar a chave:</p>

        <form action="{{ route('safra.pix.process') }}" method="POST" onsubmit="loader(this, event)">
            @csrf

            {{-- Pedido --}}
            <input type="hidden" name="merchantChargeId" value="{{ $finded_order->id }}">

            <div class="field-box">
                <label>Nome Completo</label>
                <div class="input">
                    <input type="text" name="holder-name" placeholder="FULANO SILVA" value="{{ Auth::user()->name }}">
                </div>
            </div>

            <div class="field-box">
                <label>CPF/CNPJ</label>
                <div class="input">
                    <input type="text" name="holder-doc" placeholder="65844359038" value="{{ Auth::user()->doc }}"
                        onkeyup="cpfCnpj(this)" onblur="cpfCnpj(this)">
                </div>
            </div>

            <div class="submit">
                <button type="submit" class="bt-primary-one">Gerar QR code</button>
            </div>
        </form>

        <p><a href="{{ route('orders.show_order', $finded_order->id) }}" class="bt-thirdary-one" style="text-decoration: none">← Voltar (escolher outra forma de pagamento)</a></p>

        <form action="{{ route('orders.add_coupon') }}" method="POST" class="form-coupon">
            @csrf
            <input type="hidden" name="order" value="{{ $finded_order->id }}">

            <label>Tem um cupom de desconto?</label>
            <div class="input input-and-bt-inter">
                <input type="text" name="coupon" placeholder="D9PKK5E4LGXA1GO"
                    value="{{ $finded_order->coupon ?? '' }}">
                <button class="bt-primary-one bt-inter">{{ $finded_order->coupon ? 'Atualizar' : 'Adicionar' }}
                    cupom</button>
            </div>
            {{-- <p>(saiba como conseguir o seu <a href="{{ route('cupons') }}">aqui</a>)</p> --}}
        </form>
    </div>

    <script>

        function loader(e, ev) {
            // ev.preventDefault()

            e.parentElement.children[e.parentElement.children.length - 1].style.display = 'none'

            let div = document.createElement('div')
            div.classList.add('enviando_dados_qr_code')
            div.innerHTML = 'Enviando os dados para gerar um QR Code... Aguarde.'

            e.parentElement.append(div)
        }
        function cpfCnpj(e) {
            let value = e.value.replace(/\D/g, ''); // remove tudo que não for número

            if (value.length <= 11) {
                // Formatar como CPF: 000.000.000-00
                value = value.replace(/^(\d{3})(\d)/, '$1.$2');
                value = value.replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
                value = value.replace(/\.(\d{3})(\d)/, '.$1-$2');
            } else {
                // Formatar como CNPJ: 00.000.000/0000-00
                value = value.replace(/^(\d{2})(\d)/, '$1.$2');
                value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
                value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
                value = value.replace(/(\d{4})(\d)/, '$1-$2');
            }

            e.value = value;
        }

        function createMsg(msg_class, content) {
            let div = document.createElement('div')
            div.classList.add(msg_class)
            div.innerHTML = content
            return div
        }
    </script>
@endsection
