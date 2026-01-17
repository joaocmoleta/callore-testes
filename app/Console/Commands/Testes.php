<?php

namespace App\Console\Commands;

use App\Helpers\CouponHelper;
use App\Jobs\CancelOrder;
use App\Jobs\CreditPagarMeProcess;
use App\Jobs\PixPagarMeProcess;
use App\Jobs\ProcessNotificationPM;
use App\Jobs\SendTotalFiles;
use App\Jobs\SolicitarColeta;
use App\Models\Author;
use App\Models\Order;
use App\Models\OrderEvent;
use App\Models\PagarmePedidos;
use App\Models\Payment;
use App\Models\Product;
use Spatie\Sitemap\SitemapGenerator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Spatie\Sitemap\Tags\Url;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;

class Testes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testes {type} {--value1=} {--value2=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executar testes na aplicação. Ex: testes:testes processar-resposta-pg-credito; testes:testes cancelamento --value1=teste --value2=teste';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->argument('type') == 'add_author') {
            Author::create([
                'title' => 'Redação',
                'description' => 'Redação da Callore Aquecedores de Toalhas'
            ]);
        }

        if ($this->argument('type') == 'testecupom') {
            $products = json_decode('[{"id":"15","name":"Aquecedor de Toalhas Callore Vers\u00e1til | Preto | 127V","slug":"aquecedor-de-toalhas-callore-versatil-preto-127v","thumbnail":"\/img\/aquecedor-de-toalhas-versatil-preto.webp","qtd":"1","value_uni":1754.54,"subtotal":1754.54}]');

            $amount_complete = CouponHelper::instance()
                ->get_amount($this->option('value1'), $products, false, $this->option('value2'));

            $this->line($this->option('value1'));
            $this->line($this->option('value2'));
            $this->line($amount_complete['amount']);
            $this->line($amount_complete['discount']);
            
            dd($amount_complete);
        }

        if ($this->argument('type') == 'ler_soap_response_total_express_tracking') {

            $response = '<?xml version="1.0" encoding="ISO-8859-1"?><SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tns="http://edi.totalexpress.com.br/soap/webservice_v24.total"><SOAP-ENV:Body><ns1:ObterTrackingResponse xmlns:ns1="urn:ObterTracking"><ObterTrackingResponse xsi:type="tns:ObterTrackingResponse"><CodigoProc xsi:type="xsd:nonNegativeInteger">1</CodigoProc><ArrayLoteRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:LoteRetorno[8]"><item xsi:type="tns:LoteRetorno"><CodRetorno xsi:type="xsd:nonNegativeInteger">489250503</CodRetorno><DataGeracao xsi:type="xsd:date">2024-05-31</DataGeracao><ArrayEncomendaRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:EncomendaRetorno[1]"><item xsi:type="tns:EncomendaRetorno"><Awb xsi:type="xsd:string">TXAT118317577tx</Awb><Pedido xsi:type="xsd:string">83</Pedido><NotaFiscal xsi:type="xsd:nonNegativeInteger">14988</NotaFiscal><SerieNotaFiscal xsi:type="xsd:string">1</SerieNotaFiscal><IdCliente xsi:type="xsd:string"></IdCliente><CodigoObjeto xsi:type="xsd:string"></CodigoObjeto><ArrayStatusTotal xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:StatusTotal[1]"><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">0</CodStatus><DescStatus xsi:type="xsd:string">ARQUIVO RECEBIDO</DescStatus><DataStatus xsi:type="xsd:dateTime">2024-05-31T11:22:45</DataStatus></item></ArrayStatusTotal></item></ArrayEncomendaRetorno></item><item xsi:type="tns:LoteRetorno"><CodRetorno xsi:type="xsd:nonNegativeInteger">497166525</CodRetorno><DataGeracao xsi:type="xsd:date">2024-07-11</DataGeracao><ArrayEncomendaRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:EncomendaRetorno[1]"><item xsi:type="tns:EncomendaRetorno"><Awb xsi:type="xsd:string">TXAU646485532tx</Awb><Pedido xsi:type="xsd:string">pedido-79</Pedido><NotaFiscal xsi:type="xsd:nonNegativeInteger">14958</NotaFiscal><SerieNotaFiscal xsi:type="xsd:string">1</SerieNotaFiscal><IdCliente xsi:type="xsd:string"></IdCliente><CodigoObjeto xsi:type="xsd:string"></CodigoObjeto><ArrayStatusTotal xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:StatusTotal[1]"><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">142</CodStatus><DescStatus xsi:type="xsd:string">EXTRAVIO</DescStatus><DataStatus xsi:type="xsd:dateTime">2024-07-11T16:35:56</DataStatus></item></ArrayStatusTotal></item></ArrayEncomendaRetorno></item><item xsi:type="tns:LoteRetorno"><CodRetorno xsi:type="xsd:nonNegativeInteger">497886278</CodRetorno><DataGeracao xsi:type="xsd:date">2024-07-15</DataGeracao><ArrayEncomendaRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:EncomendaRetorno[2]"><item xsi:type="tns:EncomendaRetorno"><Awb xsi:type="xsd:string">TXAS152311734tx</Awb><Pedido xsi:type="xsd:string">pedido-96</Pedido><NotaFiscal xsi:type="xsd:nonNegativeInteger">14610</NotaFiscal><SerieNotaFiscal xsi:type="xsd:string">1</SerieNotaFiscal><IdCliente xsi:type="xsd:string"></IdCliente><CodigoObjeto xsi:type="xsd:string"></CodigoObjeto><ArrayStatusTotal xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:StatusTotal[1]"><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">0</CodStatus><DescStatus xsi:type="xsd:string">ARQUIVO RECEBIDO</DescStatus><DataStatus xsi:type="xsd:dateTime">2024-07-15T18:12:50</DataStatus></item></ArrayStatusTotal></item><item xsi:type="tns:EncomendaRetorno"><Awb xsi:type="xsd:string">TXAS152318341tx</Awb><Pedido xsi:type="xsd:string">pedido-97</Pedido><NotaFiscal xsi:type="xsd:nonNegativeInteger">14610</NotaFiscal><SerieNotaFiscal xsi:type="xsd:string">1</SerieNotaFiscal><IdCliente xsi:type="xsd:string"></IdCliente><CodigoObjeto xsi:type="xsd:string"></CodigoObjeto><ArrayStatusTotal xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:StatusTotal[1]"><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">0</CodStatus><DescStatus xsi:type="xsd:string">ARQUIVO RECEBIDO</DescStatus><DataStatus xsi:type="xsd:dateTime">2024-07-15T18:22:26</DataStatus></item></ArrayStatusTotal></item></ArrayEncomendaRetorno></item><item xsi:type="tns:LoteRetorno"><CodRetorno xsi:type="xsd:nonNegativeInteger">504388150</CodRetorno><DataGeracao xsi:type="xsd:date">2024-08-22</DataGeracao><ArrayEncomendaRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:EncomendaRetorno[1]"><item xsi:type="tns:EncomendaRetorno"><Awb xsi:type="xsd:string">TXAS200196400tx</Awb><Pedido xsi:type="xsd:string">pedido-96</Pedido><NotaFiscal xsi:type="xsd:nonNegativeInteger">14610</NotaFiscal><SerieNotaFiscal xsi:type="xsd:string">1</SerieNotaFiscal><IdCliente xsi:type="xsd:string"></IdCliente><CodigoObjeto xsi:type="xsd:string"></CodigoObjeto><ArrayStatusTotal xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:StatusTotal[1]"><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">0</CodStatus><DescStatus xsi:type="xsd:string">ARQUIVO RECEBIDO</DescStatus><DataStatus xsi:type="xsd:dateTime">2024-08-22T16:06:45</DataStatus></item></ArrayStatusTotal></item></ArrayEncomendaRetorno></item><item xsi:type="tns:LoteRetorno"><CodRetorno xsi:type="xsd:nonNegativeInteger">504400825</CodRetorno><DataGeracao xsi:type="xsd:date">2024-08-22</DataGeracao><ArrayEncomendaRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:EncomendaRetorno[1]"><item xsi:type="tns:EncomendaRetorno"><Awb xsi:type="xsd:string">TXAU646485532tx</Awb><Pedido xsi:type="xsd:string">pedido-79</Pedido><NotaFiscal xsi:type="xsd:nonNegativeInteger">14958</NotaFiscal><SerieNotaFiscal xsi:type="xsd:string">1</SerieNotaFiscal><IdCliente xsi:type="xsd:string"></IdCliente><CodigoObjeto xsi:type="xsd:string"></CodigoObjeto><ArrayStatusTotal xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:StatusTotal[1]"><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">10</CodStatus><DescStatus xsi:type="xsd:string">SINISTRO LIQUIDADO</DescStatus><DataStatus xsi:type="xsd:dateTime">2024-08-22T17:36:36</DataStatus></item></ArrayStatusTotal></item></ArrayEncomendaRetorno></item><item xsi:type="tns:LoteRetorno"><CodRetorno xsi:type="xsd:nonNegativeInteger">505436850</CodRetorno><DataGeracao xsi:type="xsd:date">2024-08-28</DataGeracao><ArrayEncomendaRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:EncomendaRetorno[1]"><item xsi:type="tns:EncomendaRetorno"><Awb xsi:type="xsd:string">TXAS206534513tx</Awb><Pedido xsi:type="xsd:string">pedido-100</Pedido><NotaFiscal xsi:type="xsd:nonNegativeInteger">15274</NotaFiscal><SerieNotaFiscal xsi:type="xsd:string">1</SerieNotaFiscal><IdCliente xsi:type="xsd:string"></IdCliente><CodigoObjeto xsi:type="xsd:string"></CodigoObjeto><ArrayStatusTotal xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:StatusTotal[1]"><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">0</CodStatus><DescStatus xsi:type="xsd:string">ARQUIVO RECEBIDO</DescStatus><DataStatus xsi:type="xsd:dateTime">2024-08-28T11:46:38</DataStatus></item></ArrayStatusTotal></item></ArrayEncomendaRetorno></item><item xsi:type="tns:LoteRetorno"><CodRetorno xsi:type="xsd:nonNegativeInteger">505483291</CodRetorno><DataGeracao xsi:type="xsd:date">2024-08-28</DataGeracao><ArrayEncomendaRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:EncomendaRetorno[1]"><item xsi:type="tns:EncomendaRetorno"><Awb xsi:type="xsd:string">TXAS206534513tx</Awb><Pedido xsi:type="xsd:string">pedido-100</Pedido><NotaFiscal xsi:type="xsd:nonNegativeInteger">15274</NotaFiscal><SerieNotaFiscal xsi:type="xsd:string">1</SerieNotaFiscal><IdCliente xsi:type="xsd:string"></IdCliente><CodigoObjeto xsi:type="xsd:string"></CodigoObjeto><ArrayStatusTotal xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:StatusTotal[2]"><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">97</CodStatus><DescStatus xsi:type="xsd:string">INICIO DE COLETA C EDI</DescStatus><DataStatus xsi:type="xsd:dateTime">2024-08-28T17:32:41</DataStatus></item><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">83</CodStatus><DescStatus xsi:type="xsd:string">COLETA REALIZADA</DescStatus><DataStatus xsi:type="xsd:dateTime">2024-08-28T17:32:41</DataStatus></item></ArrayStatusTotal></item></ArrayEncomendaRetorno></item><item xsi:type="tns:LoteRetorno"><CodRetorno xsi:type="xsd:nonNegativeInteger">505576175</CodRetorno><DataGeracao xsi:type="xsd:date">2024-08-29</DataGeracao><ArrayEncomendaRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:EncomendaRetorno[1]"><item xsi:type="tns:EncomendaRetorno"><Awb xsi:type="xsd:string">TXAS206534513tx</Awb><Pedido xsi:type="xsd:string">pedido-100</Pedido><NotaFiscal xsi:type="xsd:nonNegativeInteger">15274</NotaFiscal><SerieNotaFiscal xsi:type="xsd:string">1</SerieNotaFiscal><IdCliente xsi:type="xsd:string"></IdCliente><CodigoObjeto xsi:type="xsd:string"></CodigoObjeto><ArrayStatusTotal xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:StatusTotal[2]"><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">68</CodStatus><DescStatus xsi:type="xsd:string">COLETA RECEBIDA NO CD</DescStatus><DataStatus xsi:type="xsd:dateTime">2024-08-29T05:30:07</DataStatus></item><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">101</CodStatus><DescStatus xsi:type="xsd:string">RECEBIDA E PROCESSADA NO CD - : PAG</DescStatus><DataStatus xsi:type="xsd:dateTime">2024-08-29T05:30:07</DataStatus></item></ArrayStatusTotal></item></ArrayEncomendaRetorno></item></ArrayLoteRetorno></ObterTrackingResponse></ns1:ObterTrackingResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>';
            $xml = str_ireplace(['SOAP-ENV:', 'SOAP:', 'ns1:'], '', $response);
            $doc = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOWARNING);

            $processar = false;
            switch ($doc->Body->ObterTrackingResponse->ObterTrackingResponse->CodigoProc) {
                case 0:
                    $this->line('0 – Cliente não autorizado a realizar o procedimento.');
                    break;
                case 1:
                    $processar = true;
                    $this->line('1 – Processado com sucesso.');
                    break;
                case 2:
                    $this->line('2 – Sistema indisponível no momento. Tentar novamente mais tarde.');
                    break;
                case 3:
                    $this->line('3 – Erro na validação XSD. XML enviado incorretamente.');
                    break;
                case 4:
                    $this->line('4 – Erro sistêmico por parte da TOTAL EXPRESS. Tentar novamente mais tarde.');
                    break;
                case 5:
                    $this->line('5- Realizada solicitação em menos de 5 minutos.');
                    break;
                default:
                    $this->line('Erro desconhecido.');
                    break;
            }

            if ($processar) {
                if ($doc->Body->ObterTrackingResponse->ObterTrackingResponse->ArrayLoteRetorno->item) {
                    foreach ($doc->Body->ObterTrackingResponse->ObterTrackingResponse->ArrayLoteRetorno->item as $item) {
                        foreach ($item->ArrayEncomendaRetorno->item as $subitem) {
                            $order = json_decode(json_encode($subitem->Pedido), TRUE)[0];
                            $this->line($order);

                            foreach ($subitem->ArrayStatusTotal->item as $status) {
                                $status_str = json_decode(json_encode($status->DescStatus), TRUE)[0];
                                $this->line($status_str);
                            }
                        }
                    }
                } else {
                    $this->line('Nenhum item trasmitido.');
                }
            } else {
                $this->line('Não processado.');
            }



            return Command::SUCCESS;
        }

        if ($this->argument('type') == 'cancelamento') {
            CancelOrder::dispatch($this->option('value1'));
            return Command::SUCCESS;
        }

        if ($this->argument('type') == 'cancelamento') {
            $this->cancelamento($this->option('value1'), $this->option('value2'));
            // $this->line($this->option('value'));
            return Command::SUCCESS;
        }

        if ($this->argument('type') == 'geral') {
            $this->line(static::class . ' linha ' . __LINE__);
            $this->line(__LINE__);
            return Command::SUCCESS;
        }

        if ($this->argument('type') == 'pagarme-teste-payload') {
            $this->testePayloadPagarme();
            return Command::SUCCESS;
        }

        if ($this->argument('type') == 'processar-resposta-webhook') {
            // $response = '{"id": "hook_51X0bDkC1bS8yZMQ","account": {"id": "acc_bGpnYRoi86Cgd743","name": "Aquecedor de Toalhas Callore - test"},"type": "order.paid","created_at": "2024-06-15T22:12:26.263Z","data": {"id": "or_M1oRDVCO5fvez5dx","code": "REZWIO8INX","amount": 20000,"currency": "BRL","closed": true,"items": [{"id": "oi_1brxjb0SR2cv1xpR","type": "product","description": "Suporte de ch\u00e3o sem rodinhas Branco","amount": 20000,"quantity": 1,"status": "active","created_at": "2024-06-15T22:12:24","updated_at": "2024-06-15T22:12:24","code": "23"}],"customer": {"id": "cus_x9NZAKNtz2ivPQ0k","name": "Jo\u00e3o Moleta","email": "contato@moleta.com.br","code": "1","document": "08201165993","document_type": "cpf","type": "individual","gender": null,"delinquent": false,"address": {"id": "addr_ZeJx3z4tpUJnomBX","line_1": "Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","line_2": "Casa","zip_code": "83024991","city": "Agara\u00fa","state": "BR-PR","country": "BR","status": "active","created_at": "2024-06-02T19:25:53","updated_at": "2024-06-02T19:25:53"},"created_at": "2024-06-02T19:25:53","updated_at": "2024-06-02T19:25:53","birthdate": "2024-01-01T00:00:00","phones": {"mobile_phone": {"country_code": "55","number": "998410336","area_code": "41"}}},"status": "paid","created_at": "2024-06-15T22:12:24","updated_at": "2024-06-15T22:12:26","closed_at": "2024-06-15T22:12:24","charges": [{"id": "ch_RaL6ob3SVTgnWEPO","code": "REZWIO8INX","amount": 20000,"paid_amount": 20000,"status": "paid","currency": "BRL","payment_method": "credit_card","paid_at": "2024-06-15T22:12:26","created_at": "2024-06-15T22:12:24","updated_at": "2024-06-15T22:12:26","customer": {"id": "cus_x9NZAKNtz2ivPQ0k","name": "Jo\u00e3o Moleta","email": "contato@moleta.com.br","code": "1","document": "08201165993","document_type": "cpf","type": "individual","gender": null,"delinquent": false,"address": {"id": "addr_ZeJx3z4tpUJnomBX","line_1": "Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","line_2": "Casa","zip_code": "83024991","city": "Agara\u00fa","state": "BR-PR","country": "BR","status": "active","created_at": "2024-06-02T19:25:53","updated_at": "2024-06-02T19:25:53"},"created_at": "2024-06-02T19:25:53","updated_at": "2024-06-02T19:25:53","birthdate": "2024-01-01T00:00:00","phones": {"mobile_phone": {"country_code": "55","number": "998410336","area_code": "41"}}},"last_transaction": {"operation_key": "533514531","id": "tran_r2A1O8aibTAX05jJ","transaction_type": "credit_card","gateway_id": "b03343ad-28d9-434f-9274-a130b5c856aa","amount": 20000,"status": "captured","success": true,"installments": 4,"statement_descriptor": "CALLORE","acquirer_name": "simulator","acquirer_tid": "892428719","acquirer_nsu": "9763","acquirer_auth_code": "725","acquirer_message": "Transa\u00e7\u00e3o capturada com sucesso","acquirer_return_code": "00","operation_type": "auth_and_capture","card": {"id": "card_gAP32x6tKcXoVxQ6","first_six_digits": "400000","last_four_digits": "0010","brand": "Visa","holder_name": "JOSE DA SILVA","exp_month": 1,"exp_year": 2030,"status": "active","type": "credit","created_at": "2024-06-02T19:25:53","updated_at": "2024-06-15T22:12:24","billing_address": {"street": "Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","complement": "Casa","zip_code": "83024991","city": "Agara\u00fa","state": "BR-PR","country": "BR","line_1": "Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","line_2": "Casa"}},"payment_type": "PAN","created_at": "2024-06-15T22:12:24","updated_at": "2024-06-15T22:12:24","gateway_response": {"code": "200","errors": []},"antifraud_response": [],"metadata": []}}],"checkouts": []}}';
            // $response = '{"id":"hook_wWY70v4EH4IQDmoa","account":{"id":"acc_bGpnYRoi86Cgd743","name":"Aquecedor de Toalhas Callore - test"},"type":"order.paid","created_at":"2024-06-16T02:14:40.7982402Z","data":{"id":"or_v3r7L4kUbIOY7nkX","code":"N7K7SJ0B5P","amount":20000,"currency":"BRL","closed":true,"items":[{"id":"oi_EnbxXKzsracAVRa5","amount":20000,"code":"23","created_at":"2024-06-16T02:14:40.3433333Z","description":"Suporte de ch\u00e3o sem rodinhas Branco","quantity":1,"status":"active","updated_at":"2024-06-16T02:14:40.3433333Z"}],"customer":{"id":"cus_x9NZAKNtz2ivPQ0k","name":"Jo\u00e3o Moleta","email":"contato@moleta.com.br","code":"1","document":"08201165993","document_type":"cpf","type":"individual","gender":null,"delinquent":false,"address":{"id":"addr_ZeJx3z4tpUJnomBX","line_1":"Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","line_2":"Casa","street":"Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","complement":"Casa","zip_code":"83024991","city":"Agara\u00fa","state":"BR-PR","country":"BR","status":"active","created_at":"2024-06-02T19:25:53.857Z","updated_at":"2024-06-02T19:25:53.857Z","metadata":[]},"created_at":"2024-06-02T19:25:53.823Z","updated_at":"2024-06-02T19:25:53.823Z","birthdate":"2024-01-01T00:00:00Z","phones":{"mobile_phone":{"country_code":"55","number":"998410336","area_code":"41"}},"metadata":[]},"status":"paid","created_at":"2024-06-16T02:14:40.343Z","updated_at":"2024-06-16T02:14:40.6531727Z","closed_at":"2024-06-16T02:14:40.343Z","ip":"52.168.67.32","charges":[{"id":"ch_0m6LlbZHjHyKJoqE","code":"N7K7SJ0B5P","gateway_id":"215016","amount":20000,"paid_amount":20000,"status":"paid","currency":"BRL","payment_method":"pix","paid_at":"2024-06-16T02:14:40.5727266Z","created_at":"2024-06-16T02:14:40.377Z","updated_at":"2024-06-16T02:14:40.6324111Z","pending_cancellation":false,"customer":{"id":"cus_x9NZAKNtz2ivPQ0k","name":"Jo\u00e3o Moleta","email":"contato@moleta.com.br","code":"1","document":"08201165993","document_type":"cpf","type":"individual","gender":null,"delinquent":false,"address":{"id":"addr_ZeJx3z4tpUJnomBX","line_1":"Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","line_2":"Casa","street":"Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","complement":"Casa","zip_code":"83024991","city":"Agara\u00fa","state":"BR-PR","country":"BR","status":"active","created_at":"2024-06-02T19:25:53.857Z","updated_at":"2024-06-02T19:25:53.857Z","metadata":[]},"created_at":"2024-06-02T19:25:53.823Z","updated_at":"2024-06-02T19:25:53.823Z","birthdate":"2024-01-01T00:00:00Z","phones":{"mobile_phone":{"country_code":"55","number":"998410336","area_code":"41"}},"metadata":[]},"last_transaction":{"transaction_type":"pix","pix_provider_tid":"215016","qr_code":"https:\/\/digital.mundipagg.com\/pix\/","qr_code_url":"https:\/\/api.pagar.me\/core\/v5\/transactions\/tran_GJlpKR14Smuq07NQ\/qrcode?payment_method=pix","end_to_end_id":"E12345678202009091221abcdef12345","payer":{"name":"Tony Stark","document":"***951352**","document_type":"CPF","bank_account":{"bank_name":"Pagarme Bank","ispb":"35245745"}},"expires_at":"2024-06-16T02:44:40.3766667Z","id":"tran_0bwdAePFmF03YzjQ","gateway_id":"215016","amount":20000,"status":"paid","success":true,"created_at":"2024-06-16T02:14:40.6324111Z","updated_at":"2024-06-16T02:14:40.6324111Z","gateway_response":[],"antifraud_response":[],"metadata":[]},"metadata":[]}],"metadata":[]}}';
            // $response = '{"id":"hook_kmMBMRXfdkSAZGo7","account":{"id":"acc_bGpnYRoi86Cgd743","name":"Aquecedor de Toalhas Callore - test"},"type":"order.paid","created_at":"2024-06-16T02:28:29.582031Z","data":{"id":"or_PwjbX4bfgfyWJAE2","code":"1LEQH0HJLD","amount":20000,"currency":"BRL","closed":true,"items":[{"id":"oi_9AoRVW0FwFdmZkbD","amount":20000,"code":"23","created_at":"2024-06-16T02:28:29.1966667Z","description":"Suporte de ch\u00e3o sem rodinhas Branco","quantity":1,"status":"active","updated_at":"2024-06-16T02:28:29.1966667Z"}],"customer":{"id":"cus_x9NZAKNtz2ivPQ0k","name":"Jo\u00e3o Moleta","email":"contato@moleta.com.br","code":"1","document":"08201165993","document_type":"cpf","type":"individual","gender":null,"delinquent":false,"address":{"id":"addr_ZeJx3z4tpUJnomBX","line_1":"Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","line_2":"Casa","street":"Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","complement":"Casa","zip_code":"83024991","city":"Agara\u00fa","state":"BR-PR","country":"BR","status":"active","created_at":"2024-06-02T19:25:53.857Z","updated_at":"2024-06-02T19:25:53.857Z","metadata":[]},"created_at":"2024-06-02T19:25:53.823Z","updated_at":"2024-06-02T19:25:53.823Z","birthdate":"2024-01-01T00:00:00Z","phones":{"mobile_phone":{"country_code":"55","number":"998410336","area_code":"41"}},"metadata":[]},"status":"paid","created_at":"2024-06-16T02:28:29.197Z","updated_at":"2024-06-16T02:28:29.4241181Z","closed_at":"2024-06-16T02:28:29.197Z","ip":"52.168.67.32","charges":[{"id":"ch_BxrV9pEHxHVNEJen","code":"1LEQH0HJLD","gateway_id":"137796","amount":20000,"paid_amount":20000,"status":"paid","currency":"BRL","payment_method":"pix","paid_at":"2024-06-16T02:28:29.3824813Z","created_at":"2024-06-16T02:28:29.197Z","updated_at":"2024-06-16T02:28:29.416302Z","pending_cancellation":false,"customer":{"id":"cus_x9NZAKNtz2ivPQ0k","name":"Jo\u00e3o Moleta","email":"contato@moleta.com.br","code":"1","document":"08201165993","document_type":"cpf","type":"individual","gender":null,"delinquent":false,"address":{"id":"addr_ZeJx3z4tpUJnomBX","line_1":"Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","line_2":"Casa","street":"Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","complement":"Casa","zip_code":"83024991","city":"Agara\u00fa","state":"BR-PR","country":"BR","status":"active","created_at":"2024-06-02T19:25:53.857Z","updated_at":"2024-06-02T19:25:53.857Z","metadata":[]},"created_at":"2024-06-02T19:25:53.823Z","updated_at":"2024-06-02T19:25:53.823Z","birthdate":"2024-01-01T00:00:00Z","phones":{"mobile_phone":{"country_code":"55","number":"998410336","area_code":"41"}},"metadata":[]},"last_transaction":{"transaction_type":"pix","pix_provider_tid":"137796","qr_code":"https:\/\/digital.mundipagg.com\/pix\/","qr_code_url":"https:\/\/api.pagar.me\/core\/v5\/transactions\/tran_K81wDymt4tjDWl7L\/qrcode?payment_method=pix","end_to_end_id":"E12345678202009091221abcdef12345","payer":{"name":"Tony Stark","document":"***951352**","document_type":"CPF","bank_account":{"bank_name":"Pagarme Bank","ispb":"35245745"}},"expires_at":"2024-06-16T02:58:29.1966667Z","id":"tran_mgnNLBPZhrf0koEz","gateway_id":"137796","amount":20000,"status":"paid","success":true,"created_at":"2024-06-16T02:28:29.416302Z","updated_at":"2024-06-16T02:28:29.416302Z","gateway_response":[],"antifraud_response":[],"metadata":[]},"metadata":[]}],"metadata":[]}}';
            // $response = '{"id":"hook_6RGYgq7InF0DZjd1","account":{"id":"acc_bGpnYRoi86Cgd743","name":"Aquecedor de Toalhas Callore - test"},"type":"order.paid","created_at":"2024-06-16T02:58:17.9294512Z","data":{"id":"or_jWb8oLDT9T4o7nRV","code":"2M1OF6F3MY","amount":40000,"currency":"BRL","closed":true,"items":[{"id":"oi_Q3XlglqhRhd80Pkx","amount":20000,"code":"23","created_at":"2024-06-16T02:58:17.3433333Z","description":"Suporte de ch\u00e3o sem rodinhas Branco","quantity":2,"status":"active","updated_at":"2024-06-16T02:58:17.3433333Z"}],"customer":{"id":"cus_x9NZAKNtz2ivPQ0k","name":"Jo\u00e3o Moleta","email":"contato@moleta.com.br","code":"1","document":"08201165993","document_type":"cpf","type":"individual","gender":null,"delinquent":false,"address":{"id":"addr_ZeJx3z4tpUJnomBX","line_1":"Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","line_2":"Casa","street":"Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","complement":"Casa","zip_code":"83024991","city":"Agara\u00fa","state":"BR-PR","country":"BR","status":"active","created_at":"2024-06-02T19:25:53.857Z","updated_at":"2024-06-02T19:25:53.857Z","metadata":[]},"created_at":"2024-06-02T19:25:53.823Z","updated_at":"2024-06-02T19:25:53.823Z","birthdate":"2024-01-01T00:00:00Z","phones":{"mobile_phone":{"country_code":"55","number":"998410336","area_code":"41"}},"metadata":[]},"status":"paid","created_at":"2024-06-16T02:58:17.343Z","updated_at":"2024-06-16T02:58:17.6229523Z","closed_at":"2024-06-16T02:58:17.343Z","ip":"52.168.67.32","charges":[{"id":"ch_A5O394EuLuRxdqE4","code":"2M1OF6F3MY","gateway_id":"399343","amount":40000,"paid_amount":40000,"status":"paid","currency":"BRL","payment_method":"pix","paid_at":"2024-06-16T02:58:17.5676632Z","created_at":"2024-06-16T02:58:17.36Z","updated_at":"2024-06-16T02:58:17.6065941Z","pending_cancellation":false,"customer":{"id":"cus_x9NZAKNtz2ivPQ0k","name":"Jo\u00e3o Moleta","email":"contato@moleta.com.br","code":"1","document":"08201165993","document_type":"cpf","type":"individual","gender":null,"delinquent":false,"address":{"id":"addr_ZeJx3z4tpUJnomBX","line_1":"Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","line_2":"Casa","street":"Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","complement":"Casa","zip_code":"83024991","city":"Agara\u00fa","state":"BR-PR","country":"BR","status":"active","created_at":"2024-06-02T19:25:53.857Z","updated_at":"2024-06-02T19:25:53.857Z","metadata":[]},"created_at":"2024-06-02T19:25:53.823Z","updated_at":"2024-06-02T19:25:53.823Z","birthdate":"2024-01-01T00:00:00Z","phones":{"mobile_phone":{"country_code":"55","number":"998410336","area_code":"41"}},"metadata":[]},"last_transaction":{"transaction_type":"pix","pix_provider_tid":"399343","qr_code":"https:\/\/digital.mundipagg.com\/pix\/","qr_code_url":"https:\/\/api.pagar.me\/core\/v5\/transactions\/tran_vqmgKyWCwCvOxpJQ\/qrcode?payment_method=pix","end_to_end_id":"E12345678202009091221abcdef12345","payer":{"name":"Tony Stark","document":"***951352**","document_type":"CPF","bank_account":{"bank_name":"Pagarme Bank","ispb":"35245745"}},"expires_at":"2024-06-16T03:28:17.36Z","id":"tran_gkj8KP9Hzs0Ge5A6","gateway_id":"399343","amount":40000,"status":"paid","success":true,"created_at":"2024-06-16T02:58:17.6065941Z","updated_at":"2024-06-16T02:58:17.6065941Z","gateway_response":[],"antifraud_response":[],"metadata":[]},"metadata":[]}],"metadata":[]}}';
            // $response = '{"id":"hook_wpm5nW1souGXjzD8","account":{"id":"acc_bGpnYRoi86Cgd743","name":"Aquecedor de Toalhas Callore - test"},"type":"order.paid","created_at":"2024-06-16T13:22:43.5987119Z","data":{"id":"or_nN0X6qAfgfN6yo4G","code":"W38YF1FX3L","amount":20000,"currency":"BRL","closed":true,"items":[{"id":"oi_E63Kz40hph7zod0a","amount":20000,"code":"25","created_at":"2024-06-16T13:22:43.24Z","description":"Suporte de ch\u00e3o sem rodinhas Bege","quantity":1,"status":"active","updated_at":"2024-06-16T13:22:43.24Z"}],"customer":{"id":"cus_x9NZAKNtz2ivPQ0k","name":"Jo\u00e3o Moleta","email":"contato@moleta.com.br","code":"1","document":"08201165993","document_type":"cpf","type":"individual","gender":null,"delinquent":false,"address":{"id":"addr_ZeJx3z4tpUJnomBX","line_1":"Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","line_2":"Casa","street":"Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","complement":"Casa","zip_code":"83024991","city":"Agara\u00fa","state":"BR-PR","country":"BR","status":"active","created_at":"2024-06-02T19:25:53.857Z","updated_at":"2024-06-02T19:25:53.857Z","metadata":[]},"created_at":"2024-06-02T19:25:53.823Z","updated_at":"2024-06-02T19:25:53.823Z","birthdate":"2024-01-01T00:00:00Z","phones":{"mobile_phone":{"country_code":"55","number":"998410336","area_code":"41"}},"metadata":[]},"status":"paid","created_at":"2024-06-16T13:22:43.24Z","updated_at":"2024-06-16T13:22:43.4545966Z","closed_at":"2024-06-16T13:22:43.24Z","ip":"52.168.67.32","charges":[{"id":"ch_0d7V4BWsKsVJx53N","code":"W38YF1FX3L","gateway_id":"975890","amount":20000,"paid_amount":20000,"status":"paid","currency":"BRL","payment_method":"pix","paid_at":"2024-06-16T13:22:43.4158843Z","created_at":"2024-06-16T13:22:43.257Z","updated_at":"2024-06-16T13:22:43.4497536Z","pending_cancellation":false,"customer":{"id":"cus_x9NZAKNtz2ivPQ0k","name":"Jo\u00e3o Moleta","email":"contato@moleta.com.br","code":"1","document":"08201165993","document_type":"cpf","type":"individual","gender":null,"delinquent":false,"address":{"id":"addr_ZeJx3z4tpUJnomBX","line_1":"Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","line_2":"Casa","street":"Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","complement":"Casa","zip_code":"83024991","city":"Agara\u00fa","state":"BR-PR","country":"BR","status":"active","created_at":"2024-06-02T19:25:53.857Z","updated_at":"2024-06-02T19:25:53.857Z","metadata":[]},"created_at":"2024-06-02T19:25:53.823Z","updated_at":"2024-06-02T19:25:53.823Z","birthdate":"2024-01-01T00:00:00Z","phones":{"mobile_phone":{"country_code":"55","number":"998410336","area_code":"41"}},"metadata":[]},"last_transaction":{"transaction_type":"pix","pix_provider_tid":"975890","qr_code":"https:\/\/digital.mundipagg.com\/pix\/","qr_code_url":"https:\/\/api.pagar.me\/core\/v5\/transactions\/tran_0y46X40CqC7Xj8nJ\/qrcode?payment_method=pix","end_to_end_id":"E12345678202009091221abcdef12345","payer":{"name":"Tony Stark","document":"***951352**","document_type":"CPF","bank_account":{"bank_name":"Pagarme Bank","ispb":"35245745"}},"expires_at":"2024-06-16T13:52:43.2566667Z","id":"tran_kvWOpQli3iBXwa0g","gateway_id":"975890","amount":20000,"status":"paid","success":true,"created_at":"2024-06-16T13:22:43.4497768Z","updated_at":"2024-06-16T13:22:43.4497768Z","gateway_response":[],"antifraud_response":[],"metadata":[]},"metadata":[]}],"metadata":[]}}';
            $response = '{"id":"hook_dOJ2B3EuRF0N0Ke1","account":{"id":"acc_bGpnYRoi86Cgd743","name":"Aquecedor de Toalhas Callore - test"},"type":"order.paid","created_at":"2024-06-16T21:21:08.7989022Z","data":{"id":"or_WpD3XYgcAlHYag7B","code":"6EZZFQT6ME","amount":20000,"currency":"BRL","closed":true,"items":[{"id":"oi_zqrKGbkcDf46XPoB","amount":20000,"code":"25","created_at":"2024-06-16T21:21:08.1233333Z","description":"Suporte de ch\u00e3o sem rodinhas Bege","quantity":1,"status":"active","updated_at":"2024-06-16T21:21:08.1233333Z"}],"customer":{"id":"cus_x9NZAKNtz2ivPQ0k","name":"Jo\u00e3o Moleta","email":"contato@moleta.com.br","code":"1","document":"08201165993","document_type":"cpf","type":"individual","gender":null,"delinquent":false,"address":{"id":"addr_ZeJx3z4tpUJnomBX","line_1":"Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","line_2":"Casa","street":"Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","complement":"Casa","zip_code":"83024991","city":"Agara\u00fa","state":"BR-PR","country":"BR","status":"active","created_at":"2024-06-02T19:25:53.857Z","updated_at":"2024-06-02T19:25:53.857Z","metadata":[]},"created_at":"2024-06-02T19:25:53.823Z","updated_at":"2024-06-02T19:25:53.823Z","birthdate":"2024-01-01T00:00:00Z","phones":{"mobile_phone":{"country_code":"55","number":"998410336","area_code":"41"}},"metadata":[]},"status":"paid","created_at":"2024-06-16T21:21:08.123Z","updated_at":"2024-06-16T21:21:08.4559169Z","closed_at":"2024-06-16T21:21:08.123Z","ip":"52.168.67.32","charges":[{"id":"ch_QL6jOv8h2eI3Kynv","code":"6EZZFQT6ME","gateway_id":"791650","amount":20000,"paid_amount":20000,"status":"paid","currency":"BRL","payment_method":"pix","paid_at":"2024-06-16T21:21:08.4107669Z","created_at":"2024-06-16T21:21:08.14Z","updated_at":"2024-06-16T21:21:08.4559169Z","pending_cancellation":false,"customer":{"id":"cus_x9NZAKNtz2ivPQ0k","name":"Jo\u00e3o Moleta","email":"contato@moleta.com.br","code":"1","document":"08201165993","document_type":"cpf","type":"individual","gender":null,"delinquent":false,"address":{"id":"addr_ZeJx3z4tpUJnomBX","line_1":"Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","line_2":"Casa","street":"Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","complement":"Casa","zip_code":"83024991","city":"Agara\u00fa","state":"BR-PR","country":"BR","status":"active","created_at":"2024-06-02T19:25:53.857Z","updated_at":"2024-06-02T19:25:53.857Z","metadata":[]},"created_at":"2024-06-02T19:25:53.823Z","updated_at":"2024-06-02T19:25:53.823Z","birthdate":"2024-01-01T00:00:00Z","phones":{"mobile_phone":{"country_code":"55","number":"998410336","area_code":"41"}},"metadata":[]},"last_transaction":{"transaction_type":"pix","pix_provider_tid":"791650","qr_code":"https:\/\/digital.mundipagg.com\/pix\/","qr_code_url":"https:\/\/api.pagar.me\/core\/v5\/transactions\/tran_R0nw41vCqrIN4Mja\/qrcode?payment_method=pix","end_to_end_id":"E12345678202009091221abcdef12345","payer":{"name":"Tony Stark","document":"***951352**","document_type":"CPF","bank_account":{"bank_name":"Pagarme Bank","ispb":"35245745"}},"expires_at":"2024-06-16T21:51:08.14Z","id":"tran_rJ7beybUgkfozmaL","gateway_id":"791650","amount":20000,"status":"paid","success":true,"created_at":"2024-06-16T21:21:08.4559169Z","updated_at":"2024-06-16T21:21:08.4559169Z","gateway_response":[],"antifraud_response":[],"metadata":[]},"metadata":[]}],"metadata":[]}}';

            ProcessNotificationPM::dispatch($response);

            return Command::SUCCESS;
        }

        if ($this->argument('type') == 'processar-resposta-pg-pix') {
            $response_paid = '{"id":"hook_21xaWV3frfkljbop","account":{"id":"acc_bGpnYRoi86Cgd743","name":"Aquecedor de Toalhas Callore - test"},"type":"order.paid","created_at":"2024-06-15T22:18:04.757Z","data":{"id":"or_24lYkBrhXhzkaByW","code":"L49PI3IDVP","amount":40000,"currency":"BRL","closed":true,"items":[{"id":"oi_pnZGr68UKUWY3veW","type":"product","description":"Suporte de ch\u00e3o sem rodinhas Branco","amount":20000,"quantity":2,"status":"active","created_at":"2024-06-15T22:18:04","updated_at":"2024-06-15T22:18:04","code":"23"}],"customer":{"id":"cus_x9NZAKNtz2ivPQ0k","name":"Jo\u00e3o Moleta","email":"contato@moleta.com.br","code":"1","document":"08201165993","document_type":"cpf","type":"individual","gender":null,"delinquent":false,"address":{"id":"addr_ZeJx3z4tpUJnomBX","line_1":"Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","line_2":"Casa","zip_code":"83024991","city":"Agara\u00fa","state":"BR-PR","country":"BR","status":"active","created_at":"2024-06-02T19:25:53","updated_at":"2024-06-02T19:25:53"},"created_at":"2024-06-02T19:25:53","updated_at":"2024-06-02T19:25:53","birthdate":"2024-01-01T00:00:00","phones":{"mobile_phone":{"country_code":"55","number":"998410336","area_code":"41"}}},"status":"paid","created_at":"2024-06-15T22:18:04","updated_at":"2024-06-15T22:18:04","closed_at":"2024-06-15T22:18:04","charges":[{"id":"ch_L6MGn7eIbIxKR4d5","code":"L49PI3IDVP","amount":46832,"paid_amount":46832,"status":"paid","currency":"BRL","payment_method":"credit_card","paid_at":"2024-06-15T22:18:04","created_at":"2024-06-15T22:18:04","updated_at":"2024-06-15T22:18:04","customer":{"id":"cus_x9NZAKNtz2ivPQ0k","name":"Jo\u00e3o Moleta","email":"contato@moleta.com.br","code":"1","document":"08201165993","document_type":"cpf","type":"individual","gender":null,"delinquent":false,"address":{"id":"addr_ZeJx3z4tpUJnomBX","line_1":"Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","line_2":"Casa","zip_code":"83024991","city":"Agara\u00fa","state":"BR-PR","country":"BR","status":"active","created_at":"2024-06-02T19:25:53","updated_at":"2024-06-02T19:25:53"},"created_at":"2024-06-02T19:25:53","updated_at":"2024-06-02T19:25:53","birthdate":"2024-01-01T00:00:00","phones":{"mobile_phone":{"country_code":"55","number":"998410336","area_code":"41"}}},"last_transaction":{"operation_key":"936046782","id":"tran_dvoVW6rhjhEW0Zl3","transaction_type":"credit_card","gateway_id":"e54254c8-eb99-4bb6-b0ce-eb3d88cf9a53","amount":46832,"status":"captured","success":true,"installments":11,"statement_descriptor":"CALLORE","acquirer_name":"simulator","acquirer_tid":"602835051","acquirer_nsu":"79050","acquirer_auth_code":"178","acquirer_message":"Transa\u00e7\u00e3o capturada com sucesso","acquirer_return_code":"00","operation_type":"auth_and_capture","card":{"id":"card_gAP32x6tKcXoVxQ6","first_six_digits":"400000","last_four_digits":"0010","brand":"Visa","holder_name":"JOSE DA SILVA","exp_month":1,"exp_year":2030,"status":"active","type":"credit","created_at":"2024-06-02T19:25:53","updated_at":"2024-06-15T22:18:04","billing_address":{"street":"Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","complement":"Casa","zip_code":"83024991","city":"Agara\u00fa","state":"BR-PR","country":"BR","line_1":"Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","line_2":"Casa"}},"payment_type":"PAN","created_at":"2024-06-15T22:18:04","updated_at":"2024-06-15T22:18:04","gateway_response":{"code":"200","errors":[]},"antifraud_response":[],"metadata":[]}}],"checkouts":[]}}';

            PixPagarMeProcess::dispatch([$response_paid, 152]);

            return Command::SUCCESS;
        }

        if ($this->argument('type') == 'processar-resposta-pg-credito') {
            $response_paid = '{"id": "or_J5NyL4gflCK7dwo9","code": "VJ69CQQF5L","amount": 20000,"currency": "BRL","closed": true,"items": [{"id": "oi_alKDqvsqvTg97wP9","type": "product","description": "Suporte de chão sem rodinhas Branco","amount": 20000,"quantity": 1,"status": "active","created_at": "2024-06-14T17:39:06Z","updated_at": "2024-06-14T17:39:06Z","code": "23"}],"customer": {"id": "cus_x9NZAKNtz2ivPQ0k","name": "João Moleta","email": "contato@moleta.com.br","code": "1","document": "08201165993","document_type": "cpf","type": "individual","gender": "","delinquent": false,"address": {"id": "addr_ZeJx3z4tpUJnomBX","line_1": "Rua Demétrio Zanão, 205, Agaraú","line_2": "Casa","zip_code": "83024991","city": "Agaraú","state": "BR-PR","country": "BR","status": "active","created_at": "2024-06-02T19:25:53Z","updated_at": "2024-06-02T19:25:53Z"},"created_at": "2024-06-02T19:25:53Z","updated_at": "2024-06-02T19:25:53Z","birthdate": "2024-01-01T00:00:00Z","phones": {"mobile_phone": {"country_code": "55","number": "998410336","area_code": "41"}}},"status": "paid","created_at": "2024-06-14T17:39:06Z","updated_at": "2024-06-14T17:39:08Z","closed_at": "2024-06-14T17:39:06Z","charges": [{"id": "ch_0EQdZmXs1cLgJz7q","code": "VJ69CQQF5L","gateway_id": "2204927041","amount": 18000,"paid_amount": 18000,"status": "paid","currency": "BRL","payment_method": "credit_card","paid_at": "2024-06-14T17:39:08Z","created_at": "2024-06-14T17:39:06Z","updated_at": "2024-06-14T17:39:08Z","customer": {"id": "cus_x9NZAKNtz2ivPQ0k","name": "João Moleta","email": "contato@moleta.com.br","code": "1","document": "08201165993","document_type": "cpf","type": "individual","gender": "","delinquent": false,"address": {"id": "addr_ZeJx3z4tpUJnomBX","line_1": "Rua Demétrio Zanão, 205, Agaraú","line_2": "Casa","zip_code": "83024991","city": "Agaraú","state": "BR-PR","country": "BR","status": "active","created_at": "2024-06-02T19:25:53Z","updated_at": "2024-06-02T19:25:53Z"},"created_at": "2024-06-02T19:25:53Z","updated_at": "2024-06-02T19:25:53Z","birthdate": "2024-01-01T00:00:00Z","phones": {"mobile_phone": {"country_code": "55","number": "998410336","area_code": "41"}}},"last_transaction": {"id": "tran_J0rpkW3c6c2kY8yO","transaction_type": "credit_card","gateway_id": "2204927041","amount": 18000,"status": "captured","success": true,"installments": 4,"statement_descriptor": "CALLORE","acquirer_name": "pagarme","acquirer_tid": "2204927041","acquirer_nsu": "2204927041","acquirer_auth_code": "211139","acquirer_message": "Transação aprovada com sucesso","acquirer_return_code": "0000","operation_type": "auth_and_capture","card": {"id": "card_gAP32x6tKcXoVxQ6","first_six_digits": "400000","last_four_digits": "0010","brand": "Visa","holder_name": "JOSE DA SILVA","exp_month": 1,"exp_year": 2030,"status": "active","type": "credit","created_at": "2024-06-02T19:25:53Z","updated_at": "2024-06-14T17:39:06Z","billing_address": {"street": "Rua Demétrio Zanão, 205, Agaraú","complement": "Casa","zip_code": "83024991","city": "Agaraú","state": "BR-PR","country": "BR","line_1": "Rua Demétrio Zanão, 205, Agaraú","line_2": "Casa"}},"funding_source": "credit","created_at": "2024-06-14T17:39:06Z","updated_at": "2024-06-14T17:39:06Z","gateway_response": {"code": "200","errors": []},"antifraud_response": {"status": "approved","score": "very_low","provider_name": "pagarme"},"metadata": {}}}],"checkouts": []}';
            $response_failed = '{"id": "or_DxEzoQ3sAYTWwBbe","code": "VZD1B92CQD","amount": 20000,"currency": "BRL","closed": true,"items": [{"id": "oi_v9epoNzH6c70W0Qk","type": "product","description": "Suporte de chão sem rodinhas Branco","amount": 20000,"quantity": 1,"status": "active","created_at": "2024-06-15T13:05:08Z","updated_at": "2024-06-15T13:05:08Z","code": "23"}],"customer": {"id": "cus_x9NZAKNtz2ivPQ0k","name": "João Moleta","email": "contato@moleta.com.br","code": "1","document": "08201165993","document_type": "cpf","type": "individual","gender": "","delinquent": false,"address": {"id": "addr_ZeJx3z4tpUJnomBX","line_1": "Rua Demétrio Zanão, 205, Agaraú","line_2": "Casa","zip_code": "83024991","city": "Agaraú","state": "BR-PR","country": "BR","status": "active","created_at": "2024-06-02T19:25:53Z","updated_at": "2024-06-02T19:25:53Z"},"created_at": "2024-06-02T19:25:53Z","updated_at": "2024-06-02T19:25:53Z","birthdate": "2024-01-01T00:00:00Z","phones": {"mobile_phone": {"country_code": "55","number": "998410336","area_code": "41"}}},"status": "failed","created_at": "2024-06-15T13:05:08Z","updated_at": "2024-06-15T13:05:09Z","closed_at": "2024-06-15T13:05:08Z","charges": [{"id": "ch_G5XydO1SBt3oDwlQ","code": "VZD1B92CQD","amount": 20000,"status": "failed","currency": "BRL","payment_method": "credit_card","created_at": "2024-06-15T13:05:08Z","updated_at": "2024-06-15T13:05:09Z","customer": {"id": "cus_x9NZAKNtz2ivPQ0k","name": "João Moleta","email": "contato@moleta.com.br","code": "1","document": "08201165993","document_type": "cpf","type": "individual","gender": "","delinquent": false,"address": {"id": "addr_ZeJx3z4tpUJnomBX","line_1": "Rua Demétrio Zanão, 205, Agaraú","line_2": "Casa","zip_code": "83024991","city": "Agaraú","state": "BR-PR","country": "BR","status": "active","created_at": "2024-06-02T19:25:53Z","updated_at": "2024-06-02T19:25:53Z"},"created_at": "2024-06-02T19:25:53Z","updated_at": "2024-06-02T19:25:53Z","birthdate": "2024-01-01T00:00:00Z","phones": {"mobile_phone": {"country_code": "55","number": "998410336","area_code": "41"}}},"last_transaction": {"operation_key": "76612222","id": "tran_AlJ91LpTPFPV1PnO","transaction_type": "credit_card","gateway_id": "e90e4a34-39fd-4417-b220-f5cd448d7c46","amount": 20000,"status": "not_authorized","success": false,"installments": 4,"statement_descriptor": "CALLORE","acquirer_name": "simulator","acquirer_tid": "260502746","acquirer_nsu": "13644","acquirer_message": "Transação não autorizada","acquirer_return_code": "01","operation_type": "auth_and_capture","card": {"id": "card_7ElAMNmF3FOQ1Wq6","first_six_digits": "400000","last_four_digits": "0028","brand": "Visa","holder_name": "JOSE DA SILVA","exp_month": 1,"exp_year": 2030,"status": "active","type": "credit","created_at": "2024-06-14T22:01:48Z","updated_at": "2024-06-15T13:05:08Z","billing_address": {"street": "Rua Demétrio Zanão, 205, Agaraú","complement": "Casa","zip_code": "83024991","city": "Agaraú","state": "BR-PR","country": "BR","line_1": "Rua Demétrio Zanão, 205, Agaraú","line_2": "Casa"}},"payment_type": "PAN","created_at": "2024-06-15T13:05:08Z","updated_at": "2024-06-15T13:05:08Z","gateway_response": {"code": "200","errors": []},"antifraud_response": {},"metadata": {}}}],"checkouts": []}';
            $response_paid = '{ "id": "or_24lYkBrhXhzkaByW", "code": "L49PI3IDVP", "amount": 40000, "currency": "BRL", "closed": true, "items": [ { "id": "oi_pnZGr68UKUWY3veW", "type": "product", "description": "Suporte de chão sem rodinhas Branco", "amount": 20000, "quantity": 2, "status": "active", "created_at": "2024-06-15T22:18:04Z", "updated_at": "2024-06-15T22:18:04Z", "code": "23" } ], "customer": { "id": "cus_x9NZAKNtz2ivPQ0k", "name": "João Moleta", "email": "contato@moleta.com.br", "code": "1", "document": "08201165993", "document_type": "cpf", "type": "individual", "gender": "", "delinquent": false, "address": { "id": "addr_ZeJx3z4tpUJnomBX", "line_1": "Rua Demétrio Zanão, 205, Agaraú", "line_2": "Casa", "zip_code": "83024991", "city": "Agaraú", "state": "BR-PR", "country": "BR", "status": "active", "created_at": "2024-06-02T19:25:53Z", "updated_at": "2024-06-02T19:25:53Z" }, "created_at": "2024-06-02T19:25:53Z", "updated_at": "2024-06-02T19:25:53Z", "birthdate": "2024-01-01T00:00:00Z", "phones": { "mobile_phone": { "country_code": "55", "number": "998410336", "area_code": "41" } } }, "status": "paid", "created_at": "2024-06-15T22:18:04Z", "updated_at": "2024-06-15T22:18:04Z", "closed_at": "2024-06-15T22:18:04Z", "charges": [ { "id": "ch_L6MGn7eIbIxKR4d5", "code": "L49PI3IDVP", "amount": 46832, "paid_amount": 46832, "status": "paid", "currency": "BRL", "payment_method": "credit_card", "paid_at": "2024-06-15T22:18:04Z", "created_at": "2024-06-15T22:18:04Z", "updated_at": "2024-06-15T22:18:04Z", "customer": { "id": "cus_x9NZAKNtz2ivPQ0k", "name": "João Moleta", "email": "contato@moleta.com.br", "code": "1", "document": "08201165993", "document_type": "cpf", "type": "individual", "gender": "", "delinquent": false, "address": { "id": "addr_ZeJx3z4tpUJnomBX", "line_1": "Rua Demétrio Zanão, 205, Agaraú", "line_2": "Casa", "zip_code": "83024991", "city": "Agaraú", "state": "BR-PR", "country": "BR", "status": "active", "created_at": "2024-06-02T19:25:53Z", "updated_at": "2024-06-02T19:25:53Z" }, "created_at": "2024-06-02T19:25:53Z", "updated_at": "2024-06-02T19:25:53Z", "birthdate": "2024-01-01T00:00:00Z", "phones": { "mobile_phone": { "country_code": "55", "number": "998410336", "area_code": "41" } } }, "last_transaction": { "operation_key": "936046782", "id": "tran_dvoVW6rhjhEW0Zl3", "transaction_type": "credit_card", "gateway_id": "e54254c8-eb99-4bb6-b0ce-eb3d88cf9a53", "amount": 46832, "status": "captured", "success": true, "installments": 11, "statement_descriptor": "CALLORE", "acquirer_name": "simulator", "acquirer_tid": "602835051", "acquirer_nsu": "79050", "acquirer_auth_code": "178", "acquirer_message": "Transação capturada com sucesso", "acquirer_return_code": "00", "operation_type": "auth_and_capture", "card": { "id": "card_gAP32x6tKcXoVxQ6", "first_six_digits": "400000", "last_four_digits": "0010", "brand": "Visa", "holder_name": "JOSE DA SILVA", "exp_month": 1, "exp_year": 2030, "status": "active", "type": "credit", "created_at": "2024-06-02T19:25:53Z", "updated_at": "2024-06-15T22:18:04Z", "billing_address": { "street": "Rua Demétrio Zanão, 205, Agaraú", "complement": "Casa", "zip_code": "83024991", "city": "Agaraú", "state": "BR-PR", "country": "BR", "line_1": "Rua Demétrio Zanão, 205, Agaraú", "line_2": "Casa" } }, "payment_type": "PAN", "created_at": "2024-06-15T22:18:04Z", "updated_at": "2024-06-15T22:18:04Z", "gateway_response": { "code": "200", "errors": [] }, "antifraud_response": {}, "metadata": {} } } ], "checkouts": []}';

            CreditPagarMeProcess::dispatch([$response_paid, 152]);

            return Command::SUCCESS;
        }

        if ($this->argument('type') == 'pagarme-credito') {
            $this->pagarmeCredito();
            return Command::SUCCESS;
        }

        if ($this->argument('type') == 'pagarme-pix') {
            $this->pagarmePix();
            return Command::SUCCESS;
        }

        if ($this->argument('type') == 'tracking-total') {
            $this->trackingTotal();
            return Command::SUCCESS;
        }

        if ($this->argument('type') == 'confirmar-notificacao') {
            $retorno = '<?xml version="1.0" encoding="ISO-8859-1" standalone="yes"?><transaction><date>2023-10-23T14:50:38.000-03:00</date><code>434C1EA6-19E5-4118-8CD0-6E152580CD98</code><reference>57</reference><type>1</type><status>4</status><lastEventDate>2023-10-23T14:51:11.000-03:00</lastEventDate><paymentMethod><type>11</type><code>402</code></paymentMethod><pix><pixDate>2023-10-23T14:50:38.000-03:00</pixDate><holderName>Samuel Luiz Marques</holderName><personType>PF</personType><bankName>NU PAGAMENTOS - IP</bankName><bankAgency>0001</bankAgency><bankAccount>44158204</bankAccount><bankAccountType>PG</bankAccountType></pix><grossAmount>1510.50</grossAmount><discountAmount>0.00</discountAmount><creditorFees><intermediationRateAmount>0.00</intermediationRateAmount><intermediationFeeAmount>28.55</intermediationFeeAmount></creditorFees><netAmount>1481.95</netAmount><extraAmount>0.00</extraAmount><escrowEndDate>2023-10-23T14:50:38.000-03:00</escrowEndDate><installmentCount>1</installmentCount><itemCount>1</itemCount><items><item><id>AUTO_ID 1</id><description>outros</description><quantity>1</quantity><amount>1510.50</amount></item></items><sender><name>Samuel Marques</name><email>ssamuell@gmail.com</email><phone><areaCode>51</areaCode><number>993541987</number></phone></sender><shipping><address><street>jose joao martins</street><number>107</number><complement>ap 14</complement><district>Guarani</district><city>Novo Hamburgo</city><state>RS</state><country>BRA</country><postalCode>93520370</postalCode></address><type>3</type><cost>0.00</cost></shipping><primaryReceiver><publicKey>PUB2452826528C245D585B9488EF2CD2550</publicKey></primaryReceiver><liquidation/></transaction>';
            $retorno_arr = simplexml_load_string($retorno);

            if ($retorno_arr->reference) {
                $order = Order::select('*')
                    ->where('id', $retorno_arr->reference)
                    ->first();

                dd($order);
            }
        }

        if ($this->argument('type') == 'gerar-pdf') {
            $pdf = Pdf::loadView('dashboard.teste-pdf');
            $path = 'delivery/etiqueta-e-romaneio-' . Carbon::now()->format('ymdHis') . '.pdf';
            $pdf->setPaper('a4', 'landscape')->save(public_path($path));
        }

        if ($this->argument('type') == 'solicitar-coleta-produtos') {
            $campos = Order::select(
                'orders.id',
                'orders.products',
                'invoices.natureza',
                'invoices.numero',
                'invoices.serie',
                'invoices.data_emissao',
                'invoices.total',
                'invoices.produto',
                'invoices.chave',
                'users.name',
                'users.doc',
                'completes.street',
                'completes.number',
                'completes.complement',
                'completes.locality',
                'completes.city',
                'completes.region_code',
                'completes.postal_code',
            )
                ->leftJoin('completes', 'completes.user', '=', 'orders.user')
                ->leftJoin('invoices', 'invoices.order', '=', 'orders.id')
                ->leftJoin('users', 'orders.user', '=', 'users.id')
                ->where('orders.id', 7)
                ->first();
            // dd($campos);

            // Ver peso total pedido
            $peso_total = 0;
            foreach (json_decode($campos->products) as $product) {
                $prod = Product::select(
                    'technical_specifications.pack_sizes'
                )
                    ->leftJoin('technical_specifications', 'technical_specifications.product', 'products.id')
                    ->where('products.id', $product->id)
                    ->first();

                $peso = json_decode($prod->pack_sizes)->we;

                $peso_total += $peso * $product->qtd;

                // $this->line($peso * $product->qtd);
            }
            // Limite 30Kg 120 cm X 80 cm X 60 cm
            $this->line($peso_total);
        }

        if ($this->argument('type') == 'relatorio-produtos-titulos') {
            $campos = Product::select(
                'products.title',
                'technical_specifications.sizes',
                'technical_specifications.pack_sizes'
            )
                ->leftJoin('technical_specifications', 'technical_specifications.product', '=', 'products.id')
                ->orderBy('title')
                ->get();

            $headers = ['Produto'];

            $data = [];

            foreach ($campos as $campo) {
                // $this->line($campo->title);
                $sizes = json_decode($campo->sizes);
                $pack_sizes = json_decode($campo->pack_sizes);
                $data[] = [
                    'title' => $campo->title,
                ];
            }
            $this->table($headers, $data);
            // dd($campos);
        }
        if ($this->argument('type') == 'relatorio-produtos') {
            $campos = Product::select(
                'products.id',
                'products.title',
                'technical_specifications.sizes',
                'technical_specifications.pack_sizes'
            )
                ->leftJoin('technical_specifications', 'technical_specifications.product', '=', 'products.id')
                ->orderBy('title')
                ->get();

            $headers = ['id', 'Produto', 'Peso produto', 'Peso pacote'];

            $data = [];

            foreach ($campos as $campo) {
                // $this->line($campo->title);
                $sizes = json_decode($campo->sizes);
                $pack_sizes = json_decode($campo->pack_sizes);
                $data[] = [
                    'id' => $campo->id,
                    'title' => $campo->title,
                    'sizes' => $sizes->we . 'Kg',
                    'pack_sizes' => $pack_sizes->we . 'Kg',
                ];
            }
            $this->table($headers, $data);
            // dd($campos);
        }

        if ($this->argument('type') == 'sitemap') {
            $sitemap = SitemapGenerator::create(env('APP_URL'))
                ->hasCrawled(function (Url $url) {
                    echo $url->segment(1) . PHP_EOL;
                    echo '-----------' . PHP_EOL;
                    if ($url->segment(1) === 'carts') {
                        return;
                    }
                    if ($url->segment(1) == '') {
                        $url->setPriority(1.0);
                    }

                    return $url;
                })
                ->writeToFile(public_path('sitemap.xml'));
            if ($sitemap) {
                $this->line('Arquivo gerado com sucesso: ' . public_path('sitemap.xml'));
            }
        }

        if ($this->argument('type') == 'total-express-ambiente-check') {
            $this->line('CNPJ: ' . env('CNPJ'));
            $this->line('TOTAL_EXPRESS_ID: ' . env('TOTAL_EXPRESS_ID'));
            $this->line('TOTAL_EXPRESS_USER: ' . env('TOTAL_EXPRESS_USER'));
            $this->line('TOTAL_EXPRESS_PASSWORD: ' . env('TOTAL_EXPRESS_PASSWORD'));
            $this->line('TOTAL_EXPRESS_REGISTRA_COLETA: ' . env('TOTAL_EXPRESS_REGISTRA_COLETA'));
            $this->line('TOTAL_EXPRESS_CALCULO_FRETE: ' . env('TOTAL_EXPRESS_CALCULO_FRETE'));
        }

        if ($this->argument('type') == 'total-express') {
            $this->RegistraColeta();
            $this->line('ok');
        }

        if ($this->argument('type') == 'total-express-registra-coleta-2') {
            $this->RegistraColeta2();
            $this->line('ok');
        }

        if ($this->argument('type') == 'total-express-json') {
            SolicitarColeta::dispatch();
        }

        if ($this->argument('type') == 'retorno-total') {
            $retorno = '{"retorno":{"encomendas":[{"pedido":"Teste-1234","clienteCodigo":"","tipoServico":"","data":"2023-10-04","hora":"11:18:47","volumes":[{"awb":"TXAU339213589tx","rota":"(A)02-BHI-MG-ECT-[ECT]","codigoBarras":"TXAU339213589tx"}],"documentoFiscal":[{"serie":"1","numero":"43231"}]}]}}';
            dd(json_decode($retorno)->retorno->encomendas[0]->pedido);
            dd(json_decode($retorno)->retorno->encomendas[0]->clienteCodigo);
            dd(json_decode($retorno)->retorno->encomendas[0]->tipoServico);
            dd(json_decode($retorno)->retorno->encomendas[0]->data);
            dd(json_decode($retorno)->retorno->encomendas[0]->hora);
            dd(json_decode($retorno)->retorno->encomendas[0]->volumes);
        }

        if ($this->argument('type') == 'enviar-email-confirmacao-total') {
            $this->confirmacaoTotal();
            $this->line('ok');
        }

        return Command::SUCCESS;
    }

    private function preparaRegistraColeta()
    {
        $data = [
            'TipoServico' => 1,
            'TipoEntrega' => 0,
            'Volumes' => 2,
            'CondFrete' => 'CIF',
            'Pedido' => '763',
            'IdCliente' => '1',
            'Natureza' => 'Scrubs',
            'IsencaoIcms' => 0,
            'DestNome' => 'Raphael Ponte',
            'DestCpfCnpj' => '01573330000139',
            'DestEnd' => 'Rua Francisco Sperandio',
            'DestEndNum' => '826',
            'DestCompl' => '',
            'DestBairro' => 'Cidade Kemel',
            'DestCidade' => 'Ferraz de Vasconcelos',
            'DestEstado' => 'SP',
            'DestCep' => 8542160,
            'DestEmail' => 'compras@fernandoemarcialimpezame.com.br',
            'DestDdd' => 11,
            'DestTelefone1' => 983856692,
            'DocFiscalNFe' => [
                [
                    'NfeNumero' => 88502,
                    'NfeSerie' => 9,
                    'NfeData' => '2020-09-25',
                    'NfeValTotal' => 1212.55,
                    'NfeValProd' => 1217.22,
                    'NfeChave' => 'iABDIHCAQQABiABDIHCAUQABiABDIHCAYQABiABDIHC',
                ]
            ],
        ];

        // Chama registra Coleta
    }

    private function RegistraColeta()
    {
        if (env('APP_ENV') == 'local') {
            Log::info("env('TOTAL_EXPRESS_USER') (Registra Coleta): " . env('TOTAL_EXPRESS_USER'));
            Log::info("env('TOTAL_EXPRESS_PASSWORD') (Registra Coleta): " . env('TOTAL_EXPRESS_PASSWORD'));
            Log::info("env('TOTAL_EXPRESS_CALCULO_FRETE') (Registra Coleta): " . env('TOTAL_EXPRESS_CALCULO_FRETE'));
            Log::info("env('DELIVERY_PREPARO') (Registra Coleta): " . env('DELIVERY_PREPARO'));
            Log::info("env('CEP_ORIGEM') (Registra Coleta): " . env('CEP_ORIGEM'));
        }

        $xml = '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="urn:RegistraColeta" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:ns2="https://edi.totalexpress.com.br/soap/webservice_v24.total" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/"> <SOAP-ENV:Body> <ns1:RegistraColeta> <RegistraColetaRequest> <Encomendas> <item> <TipoServico>1</TipoServico> <TipoEntrega>0</TipoEntrega> <Volumes>2</Volumes> <CondFrete>CIF</CondFrete> <Pedido>763</Pedido> <IdCliente>1</IdCliente> <Natureza>Scrubs</Natureza> <IsencaoIcms>0</IsencaoIcms> <DestNome>Raphael Ponte</DestNome> <DestCpfCnpj>01573330000139</DestCpfCnpj> <DestEnd>Rua Francisco Sperandio</DestEnd> <DestEndNum>826</DestEndNum> <DestCompl> </DestCompl> <DestBairro>Cidade Kemel</DestBairro> <DestCidade>Ferraz de Vasconcelos</DestCidade> <DestEstado>SP</DestEstado> <DestCep>08542160</DestCep> <DestEmail>compras@fernandoemarcialimpezame.com.br</DestEmail> <DestDdd>11</DestDdd> <DestTelefone1>983856692</DestTelefone1> <DocFiscalNFe> <item> <NfeNumero>88502</NfeNumero> <NfeSerie>9</NfeSerie> <NfeData>2020-09-25</NfeData> <NfeValTotal>1212.55</NfeValTotal> <NfeValProd>1217.22</NfeValProd> <NfeChave>iABDIHCAQQABiABDIHCAUQABiABDIHCAYQABiABDIHC</NfeChave> </item> </DocFiscalNFe> </item> </Encomendas> </RegistraColetaRequest> </ns1:RegistraColeta> </SOAP-ENV:Body></SOAP-ENV:Envelope>';
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

        curl_setopt($curl, CURLOPT_USERPWD, env('TOTAL_EXPRESS_USER') . ":" . env('TOTAL_EXPRESS_PASSWORD')); /* If required */
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);

        // Debug
        fclose($out);
        $debug = ob_get_clean();
        Log::info(static::class . ' linha ' . __LINE__ . ' Debug (Registra Coleta): ' . $debug);

        $err = curl_error($curl);

        curl_close($curl);

        Log::info(static::class . ' linha ' . __LINE__ . ' curl_getinfo (Registra Coleta): ' . curl_getinfo($curl, CURLINFO_HTTP_CODE));

        if ($err) {
            Log::error(static::class . ' linha ' . __LINE__ . ' curl_error (Registra Coleta): ' . $err);
            return false;
        } else {
            Log::info(static::class . ' linha ' . __LINE__ . ' response (Registra Coleta): ' . $response);

            $xml = str_ireplace(['SOAP-ENV:', 'SOAP:', 'ns1:'], '', $response);
            $doc = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOWARNING);

            Log::info(static::class . ' linha ' . __LINE__ . ' response simple xml load string (Registra Coleta): ' . $doc);
            // $json = json_encode($doc);
            // $obj = json_decode($json);

            // Log::info(static::class . ' linha ' . __LINE__ . ' response decodificado (Registra Coleta): ' . $json);

            // if (!isset($obj->Body->calcularFreteResponse->calcularFreteResponse->DadosFrete->Prazo)) {
            //     return false;
            // }

            // return $obj->Body->calcularFreteResponse->calcularFreteResponse->DadosFrete->Prazo;
        }
    }

    private function RegistraColeta2()
    {
        if (env('TOTAL_EXPRESS_USER') == '' || env('TOTAL_EXPRESS_PASSWORD') == '') {
            Log::error(static::class . ' linha ' . __LINE__ . ' Erro nas envioments utilizadas no (Registra Coleta 2).');
            Log::info(static::class . ' linha ' . __LINE__ . ' TOTAL_EXPRESS_USER: ' . env('TOTAL_EXPRESS_USER'));
            Log::info(static::class . ' linha ' . __LINE__ . ' TOTAL_EXPRESS_PASSWORD: ' . env('TOTAL_EXPRESS_PASSWORD'));
            exit();
        }

        $fields = json_encode([
            "remetenteId" => 44432,
            "cnpj" => "92783182000132",
            "remessaCodigo" => "",
            "encomendas" => [
                [
                    "servicoTipo" => 1,
                    "entregaTipo" => "0",
                    "peso" => 1,
                    "volumes" => 1,
                    "condFrete" => "CIF",
                    "pedido" => "Teste-1234",
                    "clienteCodigo" => "",
                    "natureza" => "VENDA DE MERCADORIA D/E",
                    "icmsIsencao" => 1,
                    "destinatario" => [
                        "nome" => "ALEXADRINO FERREIRA",
                        "cpfCnpj" => "13644439885",
                        "endereco" => [
                            "logradouro" => "RUA BELO HORIZONTE",
                            "numero" => "1016",
                            "complemento" => "CASA REFERENCIA: CASA DA FRENT",
                            "pontoReferencia" => "",
                            "bairro" => "CENTRO",
                            "cidade" => "CARAPICUIBA",
                            "estado" => "SP",
                            "cep" => "38738000"
                        ],
                        "email" => ""
                    ],
                    "docFiscal" => [
                        "nfe" => [
                            [
                                "nfeNumero" => 43231,
                                "nfeSerie" => "1",
                                "nfeData" => "2023-03-24",
                                "nfeValTotal" => "11.00",
                                "nfeValProd" => "11.00",
                                "nfeChave" => "35230332958768000169550010000432311001425662"
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        if (env('TOTAL_EXPRESS_DEBUG')) {
            ob_start();
            $out = fopen('php://output', 'w');
        }

        $curl = curl_init('https://apis-qa.totalexpress.com.br/ics-edi/v1/coleta/smartLabel/registrar');

        if (env('TOTAL_EXPRESS_DEBUG')) {
            curl_setopt($curl, CURLOPT_VERBOSE, true);
            curl_setopt($curl, CURLOPT_STDERR, $out);
        }

        Log::info("fields (Registra Coleta 2): " . $fields);

        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($curl, CURLOPT_USERPWD, "calloreqa-api:fRe@1823zu"); /* If required */

        $response = curl_exec($curl);

        if (env('TOTAL_EXPRESS_DEBUG')) {
            fclose($out);
            $debug = ob_get_clean();

            Log::info(static::class . ' linha ' . __LINE__ . ' Debug (Registra Coleta 2): ' . $debug);
        }

        $err = curl_error($curl);

        curl_close($curl);

        Log::info(static::class . ' linha ' . __LINE__ . ' curl_getinfo (Registra Coleta 2): ' . curl_getinfo($curl, CURLINFO_HTTP_CODE));

        if ($err) {
            Log::error(static::class . ' linha ' . __LINE__ . ' curl_error (Registra Coleta 2): ' . curl_error($curl));
        } else {
            Log::info(static::class . ' linha ' . __LINE__ . ' retorno total express (Registra Coleta 2): ' . $response);
        }
    }

    private function trackingTotal()
    {
        $xml = '<soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:ObterTracking"><soapenv:Header/><soapenv:Body><urn:ObterTracking soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><ObterTrackingRequest xsi:type="web:ObterTrackingRequest" xmlns:web="http://edi.totalexpress.com.br/soap/webservice_v24.total"></ObterTrackingRequest></urn:ObterTracking></soapenv:Body></soapenv:Envelope>';
        $url = 'https://edi.totalexpress.com.br/webservice24.php?wsdl';

        if (env('TOTAL_EXPRESS_DEBUG')) {
            ob_start();
            $out = fopen('php://output', 'w');
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

        if (env('TOTAL_EXPRESS_DEBUG')) {
            // Debug
            curl_setopt($curl, CURLOPT_VERBOSE, true);
            curl_setopt($curl, CURLOPT_STDERR, $out);
        }

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

        if (env('TOTAL_EXPRESS_DEBUG')) {
            // Debug
            fclose($out);
            $debug = ob_get_clean();
            Log::info(static::class . ' linha ' . __LINE__ . ' Debug (Tracking Total): ' . $debug);
        }

        $err = curl_error($curl);

        curl_close($curl);

        Log::info(static::class . ' linha ' . __LINE__ . ' curl_getinfo (Tracking Total): ' . curl_getinfo($curl, CURLINFO_HTTP_CODE));

        if ($err) {
            Log::error(static::class . ' linha ' . __LINE__ . ' curl_error (Tracking Total): ' . $err);
            return false;
        } else {
            Log::info(static::class . ' linha ' . __LINE__ . ' response (Tracking Total): ' . $response);

            $xml = str_ireplace(['SOAP-ENV:', 'SOAP:', 'ns1:'], '', $response);
            $doc = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOWARNING);

            Log::info(static::class . ' linha ' . __LINE__ . ' response simple xml load string (Tracking Total): ' . $doc);
            // $json = json_encode($doc);
            // $obj = json_decode($json);

            // Log::info(static::class . ' linha ' . __LINE__ . ' response decodificado (Tracking Total): ' . $json);

            // if (!isset($obj->Body->calcularFreteResponse->calcularFreteResponse->DadosFrete->Prazo)) {
            //     return false;
            // }

            // return $obj->Body->calcularFreteResponse->calcularFreteResponse->DadosFrete->Prazo;
        }
    }

    private function confirmacaoTotal()
    {
        SendTotalFiles::dispatch(65);
    }

    private function pagarmeCredito()
    {
        $response = '{"customer":{"address":{"country":"BR","state":"BR-PR","city":"Agara\u00fa","zip_code":"83024991","line_1":"Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","line_2":"Casa"},"phones":{"mobile_phone":{"country_code":"55","area_code":"41","number":"998410336"}},"name":"Jo\u00e3o Moleta","type":"individual","email":"contato@moleta.com.br","code":1,"document":"08201165993","document_type":"CPF","gender":"","birthdate":"01\/01\/2024"},"items":[{"amount":175454,"description":"Aquecedor de Toalhas Callore Vers\u00e1til | Branco | 127V","quantity":1,"code":"11"},{"amount":232840,"description":"Aquecedor de Toalhas Callore Fam\u00edlia | Preto | 127V","quantity":2,"code":"21"}],"payments":[{"credit_card":{"card":{"billing_address":{"line_1":"Rua Dem\u00e9trio Zan\u00e3o, 205, Agara\u00fa","country":"BR","state":"BR-PR","city":"Agara\u00fa","zip_code":"83024991","line_2":"Casa"}},"card_token":"token_26LyW17u3u24aV1N","operation_type":"auth_and_capture","installments":"12","statement_descriptor":"CALLORE"},"payment_method":"credit_card","amount":681865}],"closed":true,"antifraud_enabled":true}';

        $response_obj = json_decode($response);

        // $this->line($response_obj->charges[0]->id);
        // $this->line($response_obj->charges[0]->id);

        CreditPagarMeProcess::dispatch([$response, 98]);



        return true;
    }


    private function pagarmePix()
    {
        $order_id = 108;

        Log::info("Iniciado tratamento de resposta pagamento PIX PagarMe para pedido " . $order_id . '.');

        $response = '{"id": "or_QRdNyApf9f1lAWD5","code": "E0W7FMFO2V","amount": 175454,"currency": "BRL","closed": true,"items": [{"id": "oi_PVjEZE0f7fnQKBwl","type": "product","description": "Aquecedor de Toalhas Callore Versátil | Bege | 127V","amount": 175454,"quantity": 1,"status": "active","created_at": "2024-06-03T22:06:36Z","updated_at": "2024-06-03T22:06:36Z","code": "9"}],"customer": {"id": "cus_x9NZAKNtz2ivPQ0k","name": "João Moleta","email": "contato@moleta.com.br","code": "1","document": "08201165993","document_type": "cpf","type": "individual","gender": "","delinquent": false,"address": {"id": "addr_ZeJx3z4tpUJnomBX","line_1": "Rua Demétrio Zanão, 205, Agaraú","line_2": "Casa","zip_code": "83024991","city": "Agaraú","state": "BR-PR","country": "BR","status": "active","created_at": "2024-06-02T19:25:53Z","updated_at": "2024-06-02T19:25:53Z"},"created_at": "2024-06-02T19:25:53Z","updated_at": "2024-06-02T19:25:53Z","birthdate": "2024-01-01T00:00:00Z","phones": {"mobile_phone": {"country_code": "55","number": "998410336","area_code": "41"}}},"status": "failed","created_at": "2024-06-03T22:06:36Z","updated_at": "2024-06-03T22:06:36Z","closed_at": "2024-06-03T22:06:36Z","charges": [{"id": "ch_a2n04mKhPhMjQkJR","code": "E0W7FMFO2V","amount": 175454,"status": "failed","currency": "BRL","payment_method": "pix","created_at": "2024-06-03T22:06:36Z","updated_at": "2024-06-03T22:06:36Z","customer": {"id": "cus_x9NZAKNtz2ivPQ0k","name": "João Moleta","email": "contato@moleta.com.br","code": "1","document": "08201165993","document_type": "cpf","type": "individual","gender": "","delinquent": false,"address": {"id": "addr_ZeJx3z4tpUJnomBX","line_1": "Rua Demétrio Zanão, 205, Agaraú","line_2": "Casa","zip_code": "83024991","city": "Agaraú","state": "BR-PR","country": "BR","status": "active","created_at": "2024-06-02T19:25:53Z","updated_at": "2024-06-02T19:25:53Z"},"created_at": "2024-06-02T19:25:53Z","updated_at": "2024-06-02T19:25:53Z","birthdate": "2024-01-01T00:00:00Z","phones": {"mobile_phone": {"country_code": "55","number": "998410336","area_code": "41"}}},"last_transaction": {"expires_at": "2024-06-03T22:36:36Z","id": "tran_XrAqvYof1fjg8aYK","transaction_type": "pix","amount": 175454,"status": "failed","success": false,"created_at": "2024-06-03T22:06:36Z","updated_at": "2024-06-03T22:06:36Z","gateway_response": {"code": "500","errors": [{"message": "Internal Server Error"}]},"antifraud_response": {},"metadata": {}}}]}';

        $response = json_decode($response);

        // $response->charges[0]->last_transaction->qr_code_url
        dd($response->charges[0]->last_transaction);

        Order::where('id', $order_id)
            ->update([
                'status' => $response->status,
            ]);

        $order = Order::select('payment')->where('id', $order_id)->first();

        Payment::where('id', $order->payment)->update([
            'pay_id' => $response->charges[0]->id,
        ]);

        PagarmePedidos::create([
            'order' => $order_id,
            'id_order' => $response->id,
            'code' => $response->code,
            'closed' => $response->closed,
            'pg_created_at' => $response->created_at,
            'pg_updated_at' => $response->updated_at,
            'pg_closed_at' => $response->closed_at,
            'charge_id' => $response->charges[0]->id,
            'charge_code' => $response->charges[0]->code,
            // 'gateway_id' => $response->charges[0]->gateway_id,
            // 'paid_amount' => $response->charges[0]->paid_amount,
            'qr_code_url' => $response->charges[0]->last_transaction->qr_code_url,
            'expires_at' => $response->charges[0]->last_transaction->expires_at,
            'transaction_id' => $response->charges[0]->last_transaction->id,
            'antifraud_status' => $response->charges[0]->last_transaction->antifraud_response->status,
            'antifraud_score' => $response->charges[0]->last_transaction->antifraud_response->score,
        ]);

        OrderEvent::create([
            'order' => $order_id,
            'status' => $response->status,
        ]);

        Log::info(static::class . ' linha ' . __LINE__ . ' Finalizado.');
    }

    private function testePayloadPagarme()
    {
        $payload = '{"customer": {"address": {"country": "BR","state": "BR-PR","city": "Curitiba","zip_code": "83030000","line_1": "Rua das Flores, 33, Centro","line_2": "Ap 2020"},"phones": {"mobile_phone": {"country_code": "55","area_code": "41","number": "999999999"}},"name": "Tony Stark","type": "individual","email": "tonystark@stark.com.br","code": "52","document": "29241451041","document_type": "CPF","gender": "male","birthdate": "01/01/1990"},"items": [{"amount": 2990,"description": "Aquecedor de Toalhas Versátil Branco","quantity": 1,"code": "93"}],"payments": [{"Pix": {"expires_in": 1800},"payment_method": "pix"}],"closed": true,"ip": "52.168.67.32","session_id": "322b821a","antifraud_enabled": true}';


        if (env('PAGSEGURO_DEBUG')) {
            ob_start();
            $out = fopen('php://output', 'w');
        }

        $curl = curl_init(env('PAGARME_URL'));

        if (env('PAGSEGURO_DEBUG')) {
            curl_setopt($curl, CURLOPT_VERBOSE, true);
            curl_setopt($curl, CURLOPT_STDERR, $out);
        }

        Log::info("Payload (PixPagarMe): " . $payload);

        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . base64_encode(env('PAGARME_SK') . ':' . env('PAGARME_SK_PWD')),
            'Content-Type: application/json',
        ]);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);

        Log::info(static::class . ' linha ' . __LINE__ . ' Iniciando transmissão dos dados...');

        $response = curl_exec($curl);

        if (env('PAGSEGURO_DEBUG')) {
            fclose($out);
            $debug = ob_get_clean();

            Log::info(static::class . ' linha ' . __LINE__ . ' Debug (PixPagarMe): ' . $debug);
        }

        $err = curl_error($curl);

        curl_close($curl);

        Log::info(static::class . ' linha ' . __LINE__ . ' curl_getinfo (PixPagarMe): ' . curl_getinfo($curl, CURLINFO_HTTP_CODE));

        if ($err) {
            Log::error(static::class . ' linha ' . __LINE__ . ' curl_error (PixPagarMe): ' . curl_error($curl));
        } else {
            Log::info(static::class . ' linha ' . __LINE__ . ' Retorno pagarme (PixPagarMe): ' . $response);
        }
    }

    private function cancelamento($value, $charge)
    {
        $fields = [
            'amount' => $value
        ];

        ob_start();
        $out = fopen('php://output', 'w');

        $curl = curl_init(env('PAGARME_URL_CHARGES') . '/' . $charge);

        curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt($curl, CURLOPT_STDERR, $out);

        Log::info(static::class . ' Line ' . __LINE__ . " Payload: " . json_encode($fields));

        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . base64_encode('sk_test_ff63971a3e86416191befa900acd59b0:'),
            'Content-Type: application/json',
        ]);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);

        Log::info(static::class . ' linha ' . __LINE__ . ' Iniciando transmissão dos dados...');

        $response = curl_exec($curl);

        fclose($out);
        $debug = ob_get_clean();

        Log::info(static::class . ' linha ' . __LINE__ . ' Debug: ' . $debug);

        $err = curl_error($curl);

        curl_close($curl);

        Log::info(static::class . ' linha ' . __LINE__ . ' curl_getinfo: ' . curl_getinfo($curl, CURLINFO_HTTP_CODE));

        if ($err) {
            Log::error(static::class . ' linha ' . __LINE__ . ' curl_error: ' . curl_error($curl));
        } else {
            Log::info(static::class . ' linha ' . __LINE__ . ' Retorno pagarme: ' . $response);
        }

        Log::info("Finalizado envio de ordem de cancelamento PagarMe para ordem. Com ou sem erros.");
    }
}
