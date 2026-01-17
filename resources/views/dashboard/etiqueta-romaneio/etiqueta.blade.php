<td
    style="width: 10cm; height: 15cm; overflow: hidden; border: solid thin #000; padding: 10px; font-family: sans-serif;">
    <table>
        <tr>
            <td style="width: 70%">
                <p style="padding: 0; margin: 0; font-size: .7rem">Remetente</p>
                <p style="padding: 0; margin: 0; font-size: .7rem">WECO S.A INDÚSTRIA DE EQUIPAMENTO TERMO
                    MECÂNICO
                </p>
                <p style="padding: 0; margin: 0; font-size: .7rem">Rua Joaquim Silveira, 1057 - São
                    Sebastião -
                    Porto Alegre/RS</p>
                <p style="padding: 0; margin: 0; font-size: .7rem">CEP: 37270-000</p>
                <p style="padding: 0; margin: 0; font-size: .7rem; margin-top: 10px">Destinatário</p>
                <p style="padding: 0; margin: 0; font-size: .9rem; font-weight: bold;">{{ $order->name }}
                </p>
                <p style="padding: 0; margin: 0; font-size: .9rem; font-weight: bold;">
                    {{ $order->street . ' - ' . $order->number . ' - ' . $order->complement . ' - ' . $order->locality . ' - ' . $order->city . '/' . $order->region_code }}
                </p>
                <p style="padding: 0; margin: 0; font-size: .9rem; font-weight: bold;">CEP:
                    {{ $order->postal_code }}</p>
            </td>
            <td style="width: 30%; text-align: center; vertical-align: top">
                <p style="font-size: .7rem; font-weight: bold; margin: 0">TOTAL EXPRESS</p>
                <p style="border: solid 3pt #000; padding: 5px; font-weight: bold; margin: 5px 0 0">EXP</p>
                <div style="border: solid 2pt #000; margin-top: 5px; padding: 5px">
                    <p style="margin: 0">{{ \Carbon\Carbon::parse($order->data)->format('d/m') }}</p>
                    <p style="margin: 0">{{ \Carbon\Carbon::parse($order->hora)->format('H:i') }}</p>
                </div>
            </td>
        </tr>
    </table>
    <table style="text-align: center; width: 100%; border-bottom: solid 2pt #000">
        <tr>
            <td width="50%">
                @php
                    $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
                @endphp

                <img width="98%"
                    src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($order->postal_code, $generatorPNG::TYPE_CODE_128, 1)) }}">
                <div style="background: #000; color: #fff; font-weight: bold;">{{ $order->postal_code }}
                </div>
            </td>
            <td width="50%">
                <div style="display: inline-block">
                    {!! $datamix[0] !!}
                </div>
                <div style="font-size: .8rem; border: solid 2pt #000; padding: 2px">
                    {{ json_decode($order->volumes)[0]->rota }}</div>
            </td>
        </tr>
    </table>
    <table style="text-align: center; width: 100%;">
        <tr>
            <td>
                <img style="width: 8cm; height: 2.5cm; margin-top: 5px"
                    src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode(json_decode($order->volumes)[0]->codigoBarras, $generatorPNG::TYPE_CODE_128)) }}">
                <p style="margin: 0; font-size: .9rem">{{ json_decode($order->volumes)[0]->codigoBarras }}
                </p>
                <hr>
            </td>
        </tr>
        <tr>
            <td>
                <img style="width: 8cm; height: 2.5cm"
                    src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($order->pedido, $generatorPNG::TYPE_CODE_128)) }}">
                <p style="margin: 0; font-size: .9rem">{{ $order->pedido }}</p>
                <hr>
            </td>
        </tr>
    </table>
</td>
