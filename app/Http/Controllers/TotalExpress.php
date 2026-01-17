<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TotalExpress extends Controller
{
    public function registraColeta()
    {
        $fields = [
            'TipoServico' => 1, // 1 = Serviço Expresso / 2 = Serviço Especial / 3 = Standard com Transferência Rodoviária / 4 = Entrega Fácil / 5 = Premium / 6 = Standard / 7 = Super Expresso
            'TipoEntrega' => 0, // 0 = Entrega Normal (padrão) / 1 = GoBack / 2 = RMA
            'Volumes' => 1, // Quantidade de volumes físicos (caixas)
            'CondFrete' => 'CIF', // Preencher com CIF, pois a Total Express não trabalha com a modalidade FOB
            'Pedido' => '763', // Código do pedido
            'IdCliente' => '1', // Código fornecido pelo cliente
            'Natureza' => 'Natureza da Mercadoria', // Predominante
            'IsencaoIcms' => 0,
            'DestNome' => 'Raphael Ponte', // Nome do destinatário
            'DestCpfCnpj' => '', // Informar apenas os números, inclusive zeros à esquerda, se houver.
            'DestEnd' => '', // Endereço de entrega
            'DestEndNum' => '', // Número do Endereço de entrega
            'DestCompl' => '', // Complemento do endereço de entrega
            'DestBairro' => '', // 
            'DestCidade' => '', // 
            'DestEstado' => '', // 2 PR
            'DestCep' => '60175224', // 8 - somente números
            'DestEmail' => '', // 60
            'DestDdd' => '85', // 3
            'DestTelefone1' => '', // 12 - sem o ddd
            'NfeNumero' => '88502', // Número da Nota Fiscal - 9
            'NfeSerie' => '9', // 3
            'NfeData' => '2020-09-25', // Data de emissão da Nota Fiscal
            'NfeValTotal' => '1212.55', // Valor total da Nota Fiscal - 15,2
            'NfeValProd' => '1217.22', // Valor total dos produtos - 15,2
            'NfeChave' => '', // Chave de acesso - 44
        ];

        $this->SendRegistraColeta($fields);
    }

    public function previsao()
    {
        $fields = [
            "TipoServico" => "EXP", // EXP = Expresso / ESP = Especial / PRM = Premium / STD = Standard.
            "CepDestino" => "83030000",
            "Peso" => '3,2',
            "ValorDeclarado" => '15,2',
            'TipoEntrega' => 0, // 0 = Entrega Normal (padrão) / 1 = GoBack / 2 = RMA
            'ServicoCOD' => 0, // Caso haja o serviço de COD nesta encomenda, informar TRUE. Se não houver, informar FALSE.
            'Altura' => 4, // Tamanho em centímetros
            'Largura' => 4,
            'Profundidade' => 4,
        ];
        $this->sendRequestPrevisao($fields);
    }

    private function sendRequestPrevisao($fields)
    {
        $xml = '<soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:calcularFrete"><soapenv:Header/><soapenv:Body><urn:calcularFrete soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><calcularFreteRequest xsi:type="web:calcularFreteRequest" xmlns:web="http://edi.totalexpress.com.br/soap/webservice_calculo_frete.total"><!--You may enter the following 9 items in any order--><TipoServico xsi:type="xsd:string">' . $fields['TipoServico'] . '</TipoServico><CepDestino xsi:type="xsd:nonNegativeInteger">' . $fields['CepDestino'] . '</CepDestino><Peso xsi:type="xsd:string">' . $fields['Peso'] . '</Peso><ValorDeclarado xsi:type="xsd:string">' . $fields['ValorDeclarado'] . '</ValorDeclarado><TipoEntrega xsi:type="xsd:nonNegativeInteger">' . $fields['TipoEntrega'] . '</TipoEntrega><!--Optional:--><ServicoCOD xsi:type="xsd:boolean">' . $fields['ServicoCOD'] . '</ServicoCOD><!--Optional:--><Altura xsi:type="xsd:nonNegativeInteger">' . $fields['Altura'] . '</Altura><!--Optional:--><Largura xsi:type="xsd:nonNegativeInteger">' . $fields['Largura'] . '</Largura><!--Optional:--><Profundidade xsi:type="xsd:nonNegativeInteger">' . $fields['Profundidade'] . '</Profundidade></calcularFreteRequest></urn:calcularFrete></soapenv:Body></soapenv:Envelope>';
        $url = 'https://edi.totalexpress.com.br/webservice_calculo_frete_v2.php';

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

        curl_setopt($curl, CURLOPT_USERPWD, "callore-prod:bqos5ycrju"); /* If required */
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);
        
        // Debug
        fclose($out);
        $debug = ob_get_clean();
        Log::info(static::class . ' linha ' . __LINE__ . ' Debug (Previsão de entrega): ' . $debug);

        $err = curl_error($curl);

        curl_close($curl);

        Log::info(static::class . ' linha ' . __LINE__ . ' curl_getinfo (Previsão de entrega): ' . curl_getinfo($curl, CURLINFO_HTTP_CODE));

        if ($err) {
            Log::error(static::class . ' linha ' . __LINE__ . ' curl_error (Previsão de entrega): ' . curl_error($curl));
            dd($err);
        } else {
            if(curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
                Log::error(static::class . ' linha ' . __LINE__ . ' response (Previsão de entrega): ' . $response);
            }
            dd($response);
        }
    }

    private function SendRegistraColeta($fields)
    {
        $xml = '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="urn:RegistraColeta" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:ns2="https://edi.totalexpress.com.br/soap/webservice_v24.total" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/"<SOAP-ENV:Body<ns1:RegistraColeta<RegistraColetaRequest<Encomendas<item<TipoServico>' . $fields['TipoServico'] . '</TipoServico<TipoEntrega>' . $fields['TipoEntrega'] . '</TipoEntrega<Volumes>' . $fields['Volumes'] . '</Volumes<CondFrete>' . $fields['CondFrete'] . '</CondFrete<Pedido>' . $fields['Pedido'] . '</Pedido<IdCliente>' . $fields['IdCliente'] . '</IdCliente<Natureza>' . $fields['Natureza'] . '</Natureza<IsencaoIcms>' . $fields['IsencaoIcms'] . '</IsencaoIcms<DestNome>' . $fields['DestNome'] . '</DestNome<DestCpfCnpj>' . $fields['DestCpfCnpj'] . '</DestCpfCnpj<DestEnd>' . $fields['DestEnd'] . '</DestEnd<DestEndNum>' . $fields['DestEndNum'] . '</DestEndNum<DestCompl>' . $fields['DestCompl'] . '</DestCompl<DestBairro>' . $fields['DestBairro'] . '</DestBairro<DestCidade>' . $fields['DestCidade'] . '</DestCidade<DestEstado>' . $fields['DestEstado'] . '</DestEstado<DestCep>' . $fields['DestCep'] . '</DestCep<DestEmail>' . $fields['DestEmail'] . '</DestEmail<DestDdd>' . $fields['DestDdd'] . '</DestDdd<DestTelefone1>' . $fields['DestTelefone1'] . '</DestTelefone1<DocFiscalNFe<item<NfeNumero>' . $fields['NfeNumero'] . '</NfeNumero<NfeSerie>' . $fields['NfeSerie'] . '</NfeSerie<NfeData>' . $fields['NfeData'] . '</NfeData<NfeValTotal>' . $fields['NfeValTotal'] . '</NfeValTotal<NfeValProd>' . $fields['NfeValProd'] . '</NfeValProd<NfeChave>' . $fields['NfeChave'] . '</NfeChave</item</DocFiscalNFe</item</Encomendas</RegistraColetaRequest</ns1:RegistraColeta</SOAP-ENV:Body></SOAP-ENV:Envelope>';
        $url = 'https://edi.totalexpress.com.br/webservice24.php?wsdl';

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

        curl_setopt($curl, CURLOPT_USERPWD, "callore-prod:bqos5ycrju"); /* If required */
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);

        // Debug
        fclose($out);
        $debug = ob_get_clean();
        Log::info(static::class . ' linha ' . __LINE__ . ' Debug (Registra coleta): ' . $debug);

        $err = curl_error($curl);

        curl_close($curl);

        Log::info(static::class . ' linha ' . __LINE__ . ' curl_getinfo (Registra coleta): ' . curl_getinfo($curl, CURLINFO_HTTP_CODE));

        if ($err) {
            Log::error(static::class . ' linha ' . __LINE__ . ' curl_error (Registra coleta): ' . curl_error($curl));
            dd($err);
        } else {
            Log::error(static::class . ' linha ' . __LINE__ . ' response (Registra coleta): ' . $response);
            dd($response);
        }
    }

}
