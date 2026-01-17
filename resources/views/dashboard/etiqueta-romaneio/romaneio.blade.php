<div class="romaneio-impr">
    <hr style="border-style: dashed;">
    <table style="width: 100%; margin-top: 50px">
        <tr style="vertical-align: top;">
            <td style="vertical-align: middle;">
                @if ($navegador[0])
                    <img width="200" src="{{ asset('img/total-express.webp') }}" alt="Logo Total express">
                @else
                    <img width="200" src="{{ public_path('img/total-express.webp') }}" alt="Logo Total express">
                @endif
            </td>
            <td>
                <p style="margin: 5px 0"><strong>Endereço:</strong> Avenida Piracema, 155</p>
                <p style="margin: 5px 0"><strong>Cidade/UF:</strong> Barueri/SP</p>
                <p style="margin: 5px 0"><strong>CEP:</strong> 06.460-030</p>
                <p style="margin: 5px 0"><strong>Telefone:</strong> (11) 3627-5900</p>
            </td>
            <td>
                <p style="margin: 5px 0"><strong>CNPJ:</strong> 73.939.449/0001-93</p>
                <p style="margin: 5px 0"><strong>IE:</strong> 206.214.714.111</p>
            </td>
        </tr>
    </table>
    <table style="width: 100%">
        <tr>
            <td>
                <p style="margin: 5px 0"><strong>Remetente</strong></p>
                <p style="margin: 5px 0"><strong>Empresa:</strong> WECO S.A INDÚSTRIA DE EQUIPAMENTO TERMO
                    MECÂNICO</p>
                <p style="margin: 5px 0"><strong>Endereço:</strong> Rua Joaquim Silveira, 1057 - São
                    Sebastião</p>
                <p style="margin: 5px 0"><strong>Telefone:</strong> (51) 3349-6200</p>
            </td>
            <td>
                <p style="margin: 5px 0">&nbsp;</p>
                <p style="margin: 5px 0"><strong>Cidade/UF:</strong> Porto Alegre/RS</p>
                <p style="margin: 5px 0"><strong>CEP:</strong> 91060-320</p>
            </td>
        </tr>
    </table>
    <table style="width: 100%">
        <tr style="background: #f2f2f2;">
            <td>
                <p style="margin: 5px 0">NF/Pedido</p>
            </td>
            <td>
                <p style="margin: 5px 0">Destinatário</p>
            </td>
            <td>
                <p style="margin: 5px 0">Vol</p>
            </td>
        </tr>
        <tr style="background: #f9f9f9">
            <td>
                <p style="margin: 5px 0">{{ $order->numero }}</p>
            </td>
            <td>
                <p style="margin: 5px 0">{{ $order->name }}</p>
            </td>
            <td>
                <p style="margin: 5px 0">{{ $vol[0] }}</p>
            </td>
        </tr>
    </table>
    <table style="width: 100%">
        <tr>
            <td>
                <p style="margin: 5px 0">Total de volumes:</p>
            </td>
            <td>
                <p style="margin: 5px 0">{{ $vol[0] }}</p>
            </td>
        </tr>
    </table>
    <table style="width: 100%">
        <tr>
            <td style="border-bottom: solid 1pt #000; padding: 0; margin: 0">
                <p style="margin: 5px 0">Nome motorista legível:</p>
            </td>
            <td style="border-bottom: solid 1pt #000; padding: 0; margin: 0">
                <p style="margin: 5px 0">Documento:</p>
            </td>
        </tr>
        <tr>
            <td style="border-bottom: solid 1pt #000; padding: 0; margin: 0">
                <p style="margin: 5px 0">Placa do veículo:</p>
            </td>
            <td style="border-bottom: solid 1pt #000; padding: 0; margin: 0">
                <p style="margin: 5px 0">Data da coleta:</p>
            </td>
        </tr>
    </table>
    <table style="width: 100%">
        <tr>
            <td style="border-bottom: solid 1pt #000; padding: 0; margin: 0">
                <p style="margin: 5px 0">Transportadora:</p>
            </td>
        </tr>
    </table>
</div>