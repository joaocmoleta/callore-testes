@extends('templates.main', [
    'description' => '',
    'og_title' => 'Seu pedido - Pagamento com Pix',
    'noindex' => true,
])
@section('content')
    <div class="single-message">
        @include('flash-message')
    </div>

    <div class="payment-pix-safrapay">
        <h1>Seu pedido - Pagamento com Pix</h1>
        @php
            $criado = \Carbon\Carbon::parse($safra_pedido->creationDateTime);
            $now = \Carbon\Carbon::now();
            $diff = 30 - $now->floatDiffInMinutes($criado);
        @endphp
        {{-- <p>Horário atual: {{ $now }}</p> --}}
        {{-- <p>Diferença da hora de criação para horário atual: {{ $diff }}</p> --}}
        @php
            $dif = explode('.', $diff);
            $dif_sec = number_format((0 . '.' . $dif[1]) * 60, 0);
            $dif_min = $dif[0];
        @endphp
        {{-- <p>Diferença dos minutos: {{ $dif_min }}</p> --}}
        {{-- <p>Diferença dos segundos: {{ $dif_sec }}</p> --}}


        @if ($diff > 30)
        {{-- @if (true) --}}
            <p>Você gerou um QR Code, mas ele está vencido, inicie no botão abaixo o processo novamente:</p>
            <p class=""><a href="{{ route('safra.pix.reload', $safra_pedido->order) }}"
                    class="bt-primary-one">Gerar novo QR Code</a>
            </p>
        @else
            <p>Valor a pagar: <strong>R$ {{ number_format($safra_pedido->amount / 100, 2, ',', '.') }}</strong></p>
            <p>Realize o pagamento diretamente no seu aplicativo de pagamentos ou internet Banking, para isso, scaneie o QR
                code
                abaixo:</p>
            <figure>
                <img src="{{ asset('storage/' . $safra_pedido->qrCodeImage) }}" id="qrcode" alt="QR Code pagamento pix">
                <figcaption>Código válido por <span id="minutos"></span>:<span id="segundos"></span></figcaption>
            </figure>
            <div class="items-qrcode">
                <p>Ou se preferir, copie e cole o código abaixo:</p>
                <p class="qrcode-code"><input type="text" value="{{ $safra_pedido->qrCode }}" class="qrcode"> <button
                        class="bt-primary-one" onclick="copyCode(this)">Copiar código</button></p>
            </div>
            <p class="reload-qrcode"><a href="{{ route('safra.pix.reload', $safra_pedido->order) }}"
                    class="bt-primary-one">Gerar novo QR Code</a>
            </p>
        @endif

        <p>Se já realizou o pagamento, atualize a página ou aguarde o e-mail de confirmação.</p>

        <p><a href="{{ route('safra.pix.remove', $safra_pedido->order) }}" class="bt-thirdary-one" style="text-decoration: none">← Escolher outra forma de pagamento</a></p>

    </div>
    <script>
        let min = {{ $dif_min }}
        let sec = {{ $dif_sec }}

        let segundos = setInterval(() => {
            if (sec == 0) {
                sec = 59
                if (min > 0) {
                    min -= 1
                } else {
                    clearInterval(segundos)
                    let zero = 0
                    document.getElementById("segundos").textContent = zero.toString().padStart(2, '0');
                    infoFinish()
                    return
                }
            }
            document.getElementById("minutos").textContent = min.toString().padStart(2, '0');
            document.getElementById("segundos").textContent = sec.toString().padStart(2, '0');
            sec--
        }, 1000);

        function infoFinish() {
            let tg = document.getElementById("qrcode")
            tg.style.opacity = .2

            let finish = document.createElement('div')
            finish.classList.add('finish_qrcode')
            finish.innerText = 'Expirado'
            tg.parentElement.appendChild(finish)

            let qrcode_items = document.querySelector('.items-qrcode')
            qrcode_items.style.display = 'none'

            let qr_code_reload_bt = document.querySelector('.reload-qrcode')
            qr_code_reload_bt.style.display = 'block'
        }

        function copyCode(e) {
            // infoFinish()

            copyText = e.parentElement.children[0]

            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.value);
        }
    </script>
@endsection
