<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Etiquetas {{ $order->pedido }}</title>
</head>

<style>
    @page {
        margin: 10px;
    }

    .etiquetas-impressao {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .etiqueta-impressao {
        border: solid thin #000;
        padding: 10px;
        width: 10cm;
        height: 15cm;
        page-break-after: always;
    }

    .etiquetas-impressao .line-one-impr {
        display: grid;
        grid-template-columns: 6fr 2fr;
        gap: 20px;
    }

    .etiquetas-impressao .line-one-impr .exp-impr {
        text-align: center;
    }

    .etiquetas-impressao .line-two-impr {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 10px;
    }

    .etiquetas-impressao .line-two-impr .rota-impr,
    .etiquetas-impressao .line-two-impr .bar-impr {
        text-align: center;
    }

    .etiqueta-impressao .line-three-impr {
        text-align: center;
        margin-top: 15px;
    }

    .etiqueta-impressao .line-four-impr {
        margin-top: 15px;
        text-align: center;
    }

    .romaneio-impr {
        page-break-after: always;
    }
</style>

<body>
    <div class="etiquetas-impressao">
        @foreach ($order_volumes as $key => $item)
            <div class="etiqueta-impressao">
                <div class="line-one-impr">
                    <div class="data-impr">
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
                    </div>
                    <div class="exp-impr">
                        <p style="font-size: .7rem; font-weight: bold; margin: 0">TOTAL EXPRESS</p>
                        <p style="border: solid 3pt #000; padding: 5px; font-weight: bold; margin: 5px 0 0">EXP</p>
                        <div style="border: solid 2pt #000; margin-top: 5px; padding: 5px">
                            <p style="margin: 0">{{ \Carbon\Carbon::parse($order->data)->format('d/m') }}</p>
                            <p style="margin: 0">{{ \Carbon\Carbon::parse($order->hora)->format('H:i') }}</p>
                        </div>
                    </div>
                </div>
                <div class="line-two-impr">
                    <div class="bar-impr">
                        @php
                            $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
                        @endphp

                        <img width="98%"
                            src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($order->postal_code, $generatorPNG::TYPE_CODE_128, 1)) }}">
                        <div style="background: #000; color: #fff; font-weight: bold;">{{ $order->postal_code }}</div>
                    </div>
                    <div class="rota-impr">
                        <div style="display: inline-block">
                            {!! $datamix[0] !!}
                        </div>
                        <div style="font-size: .8rem; border: solid 2pt #000; padding: 2px">
                            {{ $item->rota }}</div>
                    </div>
                </div>
                <div class="line-three-impr">
                    <img style="width: 8cm; height: 2.5cm; margin-top: 5px"
                        src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($item->awb, $generatorPNG::TYPE_CODE_128)) }}">
                    <p style="margin: 0; font-size: .9rem">{{ $item->awb }}
                    </p>
                </div>
                <div class="line-four-impr">
                    <img style="width: 8cm; height: 2.5cm"
                        src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($order->pedido, $generatorPNG::TYPE_CODE_128)) }}">
                    <p style="margin: 0; font-size: .9rem">{{ $order->pedido }}</p>
                </div>
            </div>
        @endforeach
    </div>
    @include('dashboard.etiqueta-romaneio.romaneio')
    {{-- <p>{{ $item->awb }}</p>
        <p>{{ $item->rota }}</p>
        <p>{{ $item->codigoBarras }}</p> --}}
    {{-- <table>
        
        <tr>
            @include('dashboard.etiqueta-romaneio.etiqueta')
        </tr>
        <tr>
        </tr>
    </table> --}}

</body>

</html>
