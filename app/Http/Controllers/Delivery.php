<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Delivery extends Controller
{
    public function simulateDeliveryAjax(Request $request)
    {
        Log::info(static::class . ' linha ' . __LINE__ . " Simulação de frete total express iniciada.");
        $validator = Validator::make(
            $request->all(),
            [
                'cep' => 'required|min:6',
                'height' => 'required',
                'width' => 'required',
                'length' => 'required',
                'weight' => 'required',
                'value' => 'required',
            ],
            ['cep' => 'Um CEP válido é necessário.']
        );

        if ($validator->fails()) {
            Log::error(static::class . ' linha ' . __LINE__ . " Faltou algum valor obrigatório.");
            return response()->json([$validator->errors()], 400);
        }

        // Validar cep
        $cep_clean = preg_replace('/[^0-9]/', '', $request->cep);
        if (strlen($cep_clean) != 8) {
            Log::error(static::class . ' linha ' . __LINE__ . " O CEP digitado não é válido.");
            return response()->json(['status' => 'error', 'msg' => 'Um CEP válido é necessário.'], 400);
        }

        // Local
        if(env('APP_ENV') == 'local') {
            Log::info(static::class . ' linha ' . __LINE__ . ' Simulação em teste não comunica com Total express.');
            return response()->json([
                'status' => 'success',
                'msg' => 2 + env('DELIVERY_PREPARO'),
                'origem' => env('CEP_ORIGEM'),
                'height' => $request->height,
                'width' => $request->width,
                'length' => $request->length,
                'weight' => $request->weight,
            ], 200);
        }


        // Se for o mesmo cep salvo só retornar os valores
        if(Cookie::has('cep_user')) {
            if(Cookie::get('cep_user') == $cep_clean) {
                if(Cookie::has('delivery_estimative')) {
                    Log::info(static::class . ' linha ' . __LINE__ . " Estimativa recuperada de cache.");
                    return response()->json([
                        'status' => 'success',
                        'msg' => Cookie::get('delivery_estimative') + env('DELIVERY_PREPARO'),
                        'origem' => env('CEP_ORIGEM'),
                        'height' => $request->height,
                        'width' => $request->width,
                        'length' => $request->length,
                        'weight' => $request->weight,
                    ], 200);
                }
            }
        } else {
            Cookie::queue('cep_user', $cep_clean, 43800);
        }

        $fields = [
            "TipoServico" => "EXP", // EXP = Expresso / ESP = Especial / PRM = Premium / STD = Standard.
            "CepDestino" => $cep_clean, // "83030000"
            "Peso" => str_replace('.', ',', $request->weight), // '3,2'
            "ValorDeclarado" => str_replace('.', ',', $request->value), // '15,2'
            'TipoEntrega' => 0, // 0 = Entrega Normal (padrão) / 1 = GoBack / 2 = RMA
            'ServicoCOD' => 0, // Caso haja o serviço de COD nesta encomenda, informar TRUE. Se não houver, informar FALSE.
            'Altura' => str_replace('.', ',', $request->height), // Tamanho em centímetros
            'Largura' => str_replace('.', ',', $request->width),
            'Profundidade' => str_replace('.', ',', $request->length),
        ];

        $res = $this->sendRequestPrevisao($fields);

        if ($res) {
            Cookie::queue('delivery_estimative', $res, 60 * 60 * 24);

            return response()->json([
                'status' => 'success',
                'msg' => $res + env('DELIVERY_PREPARO'),
                'origem' => env('CEP_ORIGEM'),
                'height' => $request->height,
                'width' => $request->width,
                'length' => $request->length,
                'weight' => $request->weight,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'msg' => 'Falha ao realizar a consulta, contate o administrador.',
            'origem' => env('CEP_ORIGEM'),
            'height' => $request->height,
            'width' => $request->width,
            'length' => $request->length,
            'weight' => $request->weight,
        ], 500);
    }

    private function melhorEnvio($to, $height, $width, $length, $weight)
    {
        if (env('MELHOR_ENVIO_URL') == '' || env('CEP_ORIGEM') == '' || env('MELHOR_ENVIO_EMAIL') == '' || env('MELHOR_ENVIO_TOKEN') == '') {
            Log::error(static::class . ' linha ' . __LINE__ . ' Erro nas envioments utilizadas na simulação (Melhor envio).');
            Log::info(static::class . ' linha ' . __LINE__ . ' MELHOR_ENVIO_URL: ' . env('MELHOR_ENVIO_URL'));
            Log::info(static::class . ' linha ' . __LINE__ . ' CEP_ORIGEM: ' . env('CEP_ORIGEM'));
            Log::info(static::class . ' linha ' . __LINE__ . ' MELHOR_ENVIO_EMAIL: ' . env('MELHOR_ENVIO_EMAIL'));
            Log::info(static::class . ' linha ' . __LINE__ . ' MELHOR_ENVIO_TOKEN: ' . env('MELHOR_ENVIO_TOKEN'));
            return false;
        }

        $fields = json_encode([
            "from" => [
                "postal_code" => env('CEP_ORIGEM')
            ],
            "to" => [
                "postal_code" => $to
            ],
            "package" => [
                "height" => $height,
                "width" => $width,
                "length" => $length,
                "weight" => $weight,
            ]
        ]);

        if (env('PAGSEGURO_DEBUG')) {
            ob_start();
            $out = fopen('php://output', 'w');
        }

        $curl = curl_init();

        if (env('PAGSEGURO_DEBUG')) {
            curl_setopt($curl, CURLOPT_VERBOSE, true);
            curl_setopt($curl, CURLOPT_STDERR, $out);
        }

        Log::info("fields (Melhor envio): " . $fields);

        curl_setopt_array($curl, array(
            CURLOPT_URL => env('MELHOR_ENVIO_URL') . '/api/v2/me/shipment/calculate',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POSTFIELDS => $fields,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer ' . env('MELHOR_ENVIO_TOKEN'),
                'User-Agent: Aplicação ' . env('MELHOR_ENVIO_EMAIL')
            ),
        ));

        $response = curl_exec($curl);

        if (env('PAGSEGURO_DEBUG')) {
            fclose($out);
            $debug = ob_get_clean();

            Log::info(static::class . ' linha ' . __LINE__ . ' Debug (Melhor envio): ' . $debug);
        }

        $err = curl_error($curl);

        curl_close($curl);

        Log::info(static::class . ' linha ' . __LINE__ . ' curl_getinfo (Melhor envio): ' . curl_getinfo($curl, CURLINFO_HTTP_CODE));

        if ($err) {
            Log::error(static::class . ' linha ' . __LINE__ . ' curl_error (Melhor envio): ' . curl_error($curl));
            return false;
        } else {
            Log::info(static::class . ' linha ' . __LINE__ . ' Retorno (Melhor envio): ' . $response);
            return $response;
        }
    }

    private function sendRequestPrevisao($fields)
    // public function sendRequestPrevisao()
    {
        Log::info(static::class . ' linha ' . __LINE__ . ' iniciado comunicação para cálculo de frete.');
        if(env('APP_ENV') == 'local') {
            Log::info(static::class . ' linha ' . __LINE__ . " env('TOTAL_EXPRESS_CALCULO_FRETE'): " . env('TOTAL_EXPRESS_CALCULO_FRETE'));
            Log::info(static::class . ' linha ' . __LINE__ . " env('TOTAL_EXPRESS_USER'): " . env('TOTAL_EXPRESS_USER'));
            Log::info(static::class . ' linha ' . __LINE__ . " env('TOTAL_EXPRESS_PASSWORD'): " . env('TOTAL_EXPRESS_PASSWORD'));
            Log::info(static::class . ' linha ' . __LINE__ . " env('TOTAL_EXPRESS_CALCULO_FRETE'): " . env('TOTAL_EXPRESS_CALCULO_FRETE'));
            Log::info(static::class . ' linha ' . __LINE__ . " env('DELIVERY_PREPARO'): " . env('DELIVERY_PREPARO'));
            Log::info(static::class . ' linha ' . __LINE__ . " env('CEP_ORIGEM'): " . env('CEP_ORIGEM'));
        }

        $xml = '<soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:calcularFrete"><soapenv:Header/><soapenv:Body><urn:calcularFrete soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><calcularFreteRequest xsi:type="web:calcularFreteRequest" xmlns:web="http://edi.totalexpress.com.br/soap/webservice_calculo_frete.total"><!--You may enter the following 9 items in any order--><TipoServico xsi:type="xsd:string">' . $fields['TipoServico'] . '</TipoServico><CepDestino xsi:type="xsd:nonNegativeInteger">' . $fields['CepDestino'] . '</CepDestino><Peso xsi:type="xsd:string">' . $fields['Peso'] . '</Peso><ValorDeclarado xsi:type="xsd:string">' . $fields['ValorDeclarado'] . '</ValorDeclarado><TipoEntrega xsi:type="xsd:nonNegativeInteger">' . $fields['TipoEntrega'] . '</TipoEntrega><!--Optional:--><ServicoCOD xsi:type="xsd:boolean">' . $fields['ServicoCOD'] . '</ServicoCOD><!--Optional:--><Altura xsi:type="xsd:nonNegativeInteger">' . $fields['Altura'] . '</Altura><!--Optional:--><Largura xsi:type="xsd:nonNegativeInteger">' . $fields['Largura'] . '</Largura><!--Optional:--><Profundidade xsi:type="xsd:nonNegativeInteger">' . $fields['Profundidade'] . '</Profundidade></calcularFreteRequest></urn:calcularFrete></soapenv:Body></soapenv:Envelope>';
        $url = env('TOTAL_EXPRESS_CALCULO_FRETE');

        // Debug
        ob_start();
        $out = fopen('php://output', 'w');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

        // Debug
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt($curl, CURLOPT_STDERR, $out);

        $headers = [
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "SOAPAction: $url",
        ];

        if ($xml != null) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, "$xml");
            array_push($headers, "Content-Length: " . strlen($xml));
        }

        curl_setopt($curl, CURLOPT_USERPWD, env('TOTAL_EXPRESS_CALCULO_FRETE_USER') . ":" . env('TOTAL_EXPRESS_CALCULO_FRETE_PASSWORD')); /* If required */
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);

        // Debug
        fclose($out);
        $debug = ob_get_clean();
        Log::info(static::class . ' linha ' . __LINE__ . ' Debug: ' . $debug);

        $err = curl_error($curl);

        curl_close($curl);

        Log::info(static::class . ' linha ' . __LINE__ . ' curl_getinfo: ' . curl_getinfo($curl, CURLINFO_HTTP_CODE));

        if ($err) {
            Log::error(static::class . ' linha ' . __LINE__ . ' curl_error: ' . $err);
            return false;
        } else {
            Log::info(static::class . ' linha ' . __LINE__ . ' response: ' . $response);

            $xml = str_ireplace(['SOAP-ENV:', 'SOAP:', 'ns1:'], '', $response);
            $doc = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOWARNING);
            $json = json_encode($doc);
            $obj = json_decode($json);

            Log::info(static::class . ' linha ' . __LINE__ . ' response decodificado: ' . $json);

            if (!isset($obj->Body->calcularFreteResponse->calcularFreteResponse->DadosFrete->Prazo)) {
                return false;
            }

            return $obj->Body->calcularFreteResponse->calcularFreteResponse->DadosFrete->Prazo;
        }
    }
}
