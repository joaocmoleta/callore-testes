<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Etiqueta</title>
</head>

<body>
    <div class="etiqueta">
        <div class="informacoes">
            <div class="col-1">
                <p style="font-size: .7rem">Remetente</p>
                <p style="font-size: .7rem">WECO S.A INDÚSTRIA DE EQUIPAMENTO TERMO MECÂNICO</p>
                <p style="font-size: .7rem">Rua Joaquim Silveira, 1057 - São Sebastião - Porto Alegre/RS</p>
                <p style="font-size: .7rem">CEP: 37270-000</p>
                <p style="margin-top: 10px; font-size: .7rem">Destinatário</p>
                <p style="font-size: .8rem"><strong>{{ $order->name }}</strong></p>
                <p style="font-size: .8rem"><strong>{{ $order->street }}, {{ $order->number }} - {{ $order->complement }} - {{ $order->locality }} - {{ $order->city }}/{{ $order->region_code }}</strong></p>
                <p style="font-size: .8rem"><strong>CEP: {{ $order->postal_code }}</strong></p>
            </div>
            <div class="col-2">
                <p style="margin-bottom: 10px;"><strong>TOTAL EXPRESS</strong></p>
                <div class="code">EXP</div>
                <div class="info-data">
                    <p><strong>{{ \Carbon\Carbon::parse($order->data)->format('d/m')}}</strong></p>
                    <p><strong>{{ \Carbon\Carbon::parse($order->hora)->format('H:m')}}</strong></p>
                </div>
            </div>
        </div>
        <div class="cep">
            <div class="barcode-cep">
                @php
                    $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
                @endphp

                <img width="98%"
                    src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($order->postal_code, $generatorPNG::TYPE_CODE_128, 1)) }}">
                <div class="number">{{ $order->postal_code }}</div>
            </div>
            <div class="qr-code">
                <div class="datamix">
                    {!! $datamix[0] !!}
                </div>
                <div class="number">{{ json_decode($order->volumes)[0]->rota }}</div>
            </div>
        </div>
        <div class="cod-rotas-awb">
            <img style="width: 8cm; height: 2.5cm"
                src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode(json_decode($order->volumes)[0]->codigoBarras, $generatorPNG::TYPE_CODE_128)) }}">
                <p>{{ json_decode($order->volumes)[0]->codigoBarras }}</p>
            </div>
            <div class="cod-de-identificacao">
            <img style="width: 8cm; height: 2.5cm"
                src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($order->pedido, $generatorPNG::TYPE_CODE_128)) }}">
            <p>{{ $order->pedido }}</p>
        </div>
    </div>
    <link rel="stylesheet" href="{{ asset('css/etiqueta.css') }}">
</body>

</html>
