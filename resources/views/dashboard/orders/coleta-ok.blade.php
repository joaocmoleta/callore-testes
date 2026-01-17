<div class="coleta-ok">
    {{-- <p class="bt-primary-danger">Entre em contato com o suporte do ecommerce.</p> --}}
    <p>Sua coleta foi solicitada em {{ \Carbon\Carbon::parse($order->data)->format('d/m/Y') }} às
        {{ \Carbon\Carbon::parse($order->hora)->format('H:i:s') }}.</p>
    <p>Prepare o seu produto e aguarde a retirada pela transportadora.</p>
    <p><span class="bt-primary-one" onclick="openInstructions('.instructions')">Ver as instruções</span></p>
    <div class="instructions">
        <div class="instructions-content">
            <img class="bt-close" src="{{ asset('icons/close.svg') }}" alt="Botão fechar" onclick="instructionsClose(this)">
            <script>
                function openInstructions(selector) {
                    let tg = document.querySelector(selector)
                    tg.style.display = 'flex'
                }
                function instructionsClose(e) {
                    e.parentElement.parentElement.style.display = 'none'
                }
            </script>
            <h3>Instruções Total Express</h3>
            <p><strong>Importante:</strong></p>
            <p><img src="{{ asset('icons/info.svg') }}" width="13"> Horário de corte às 10h59;</p>
            <p><img src="{{ asset('icons/info.svg') }}" width="13"> Horário de coleta: de segunda a sexta das 12h às
                18h -
                dias úteis;</p>
            <p><img src="{{ asset('icons/info.svg') }}" width="13"> Até 15 minutos motorista da coleta aguarda.</p>
            <p><img src="{{ asset('icons/warning.svg') }}" width="13"> Confira os dados de entrega, quantidade de
                volumes e
                demais dados na etiqueta e romaneio.</p>
            <br>
            <p><strong>Na embalagem:</strong></p>
            <p><img src="{{ asset('icons/check.svg') }}" width="15"> Afixar a Danfe com código de barras em local
                evidente;
            </p>
            <p><img src="{{ asset('icons/check.svg') }}" width="15"> Afixar etiqueta;</p>
            <p><img src="{{ asset('icons/check.svg') }}" width="15"> Limites de 30Kg, 120x80x60cm</p>
            <br>
            <p><strong>Em mãos:</strong></p>
            <p>Estar com romaneio impresso e preenchido para motorista da coleta assinar e preencher.</p>
        </div>
    </div>
    <br>
    <p><a href="{{ route('etiqueta.label.print', [$order->id, $order->volumes]) }}" target="_blank"
        class="bt-primary-one">Imprimir etiqueta e romaneio</a></p>
    <br>
</div>
