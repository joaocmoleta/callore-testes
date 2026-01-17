<div class="nota-fiscal">
    <form action="{{ route('nfe.save-file') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="order" value="{{ $order->id }}">
        <div class="box-input box-input-20">
            <label>Adicionar</label>
            <div class="input">
                <input type="file" name="file" value="{{ old('file') }}">
                @error('file')
                    <div class="msg-error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <p>Ao adicionar a nota fiscal, seu pedido será enviado para a Total Express, solicitando a coleta;</p>
        <p><img src="{{ asset('icons/info.svg') }}" width="13"> Horário de corte às 10h59;</p>
        <p><img src="{{ asset('icons/info.svg') }}" width="13"> Horário de coleta: de segunda a sexta das 12h às 18h
            - dias úteis;</p>
        <p><img src="{{ asset('icons/info.svg') }}" width="13"> Até 15 minutos motorista da coleta aguarda.</p>
        <p><strong>Na embalagem:</strong></p>
        <p><img src="{{ asset('icons/check.svg') }}" width="15"> Afixar a Danfe com código de barras em local
            evidente;</p>
        <p><img src="{{ asset('icons/check.svg') }}" width="15"> Afixar etiqueta;</p>
        <p><img src="{{ asset('icons/check.svg') }}" width="15"> Limites de 30Kg, 120x80x60cm</p>
        <p><strong>Em mãos:</strong></p>
        <p><img src="{{ asset('icons/check.svg') }}" width="15"> Estar com romaneio impresso e preenchido para motorista da coleta assinar e preencher.</p>

        <div class=" box-input box-input-20">
            <button class="bt-primary-one">Enviar nota</button>
        </div>
    </form>
</div>
