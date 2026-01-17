<?php

namespace App\Console\Commands;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\OrderEvent;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TrackingTotal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tracking:total';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificar os status da entrega tracking:total';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info(static::class . ' linha ' . __LINE__ . ' Tracking Total verificação de novos status.');
        // return;

        if (false) { // Para atualizações manuais
            // pedido-59
            $arquivo_recebido = '<?xml version="1.0" encoding="ISO-8859-1"?><SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tns="http://edi.totalexpress.com.br/soap/webservice_v24.total"><SOAP-ENV:Body><ns1:ObterTrackingResponse xmlns:ns1="urn:ObterTracking"><ObterTrackingResponse xsi:type="tns:ObterTrackingResponse"><CodigoProc xsi:type="xsd:nonNegativeInteger">1</CodigoProc><ArrayLoteRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:LoteRetorno[1]"><item xsi:type="tns:LoteRetorno"><CodRetorno xsi:type="xsd:nonNegativeInteger">463384463</CodRetorno><DataGeracao xsi:type="xsd:date">2023-11-24</DataGeracao><ArrayEncomendaRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:EncomendaRetorno[1]"><item xsi:type="tns:EncomendaRetorno"><Awb xsi:type="xsd:string">TXAU452841984tx</Awb><Pedido xsi:type="xsd:string">pedido-59</Pedido><NotaFiscal xsi:type="xsd:nonNegativeInteger">14610</NotaFiscal><SerieNotaFiscal xsi:type="xsd:string">1</SerieNotaFiscal><IdCliente xsi:type="xsd:string"></IdCliente><CodigoObjeto xsi:type="xsd:string"></CodigoObjeto><ArrayStatusTotal xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:StatusTotal[1]"><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">0</CodStatus><DescStatus xsi:type="xsd:string">ARQUIVO RECEBIDO</DescStatus><DataStatus xsi:type="xsd:dateTime">2023-11-24T10:03:10</DataStatus></item></ArrayStatusTotal></item></ArrayEncomendaRetorno></item></ArrayLoteRetorno></ObterTrackingResponse></ns1:ObterTrackingResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>';
            $dois_status = '<?xml version="1.0" encoding="ISO-8859-1"?><SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tns="http://edi.totalexpress.com.br/soap/webservice_v24.total"><SOAP-ENV:Body><ns1:ObterTrackingResponse xmlns:ns1="urn:ObterTracking"><ObterTrackingResponse xsi:type="tns:ObterTrackingResponse"><CodigoProc xsi:type="xsd:nonNegativeInteger">1</CodigoProc><ArrayLoteRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:LoteRetorno[2]"><item xsi:type="tns:LoteRetorno"><CodRetorno xsi:type="xsd:nonNegativeInteger">463418404</CodRetorno><DataGeracao xsi:type="xsd:date">2023-11-24</DataGeracao><ArrayEncomendaRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:EncomendaRetorno[1]"><item xsi:type="tns:EncomendaRetorno"><Awb xsi:type="xsd:string">TXAU452841984tx</Awb><Pedido xsi:type="xsd:string">pedido-59</Pedido><NotaFiscal xsi:type="xsd:nonNegativeInteger">14610</NotaFiscal><SerieNotaFiscal xsi:type="xsd:string">1</SerieNotaFiscal><IdCliente xsi:type="xsd:string"></IdCliente><CodigoObjeto xsi:type="xsd:string"></CodigoObjeto><ArrayStatusTotal xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:StatusTotal[1]"><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">83</CodStatus><DescStatus xsi:type="xsd:string">COLETA REALIZADA</DescStatus><DataStatus xsi:type="xsd:dateTime">2023-11-24T15:43:18</DataStatus></item></ArrayStatusTotal></item></ArrayEncomendaRetorno></item><item xsi:type="tns:LoteRetorno"><CodRetorno xsi:type="xsd:nonNegativeInteger">463461193</CodRetorno><DataGeracao xsi:type="xsd:date">2023-11-24</DataGeracao><ArrayEncomendaRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:EncomendaRetorno[1]"><item xsi:type="tns:EncomendaRetorno"><Awb xsi:type="xsd:string">TXAU452841984tx</Awb><Pedido xsi:type="xsd:string">pedido-59</Pedido><NotaFiscal xsi:type="xsd:nonNegativeInteger">14610</NotaFiscal><SerieNotaFiscal xsi:type="xsd:string">1</SerieNotaFiscal><IdCliente xsi:type="xsd:string"></IdCliente><CodigoObjeto xsi:type="xsd:string"></CodigoObjeto><ArrayStatusTotal xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:StatusTotal[2]"><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">68</CodStatus><DescStatus xsi:type="xsd:string">COLETA RECEBIDA NO CD</DescStatus><DataStatus xsi:type="xsd:dateTime">2023-11-24T23:32:28</DataStatus></item><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">101</CodStatus><DescStatus xsi:type="xsd:string">RECEBIDA E PROCESSADA NO CD - : PAG</DescStatus><DataStatus xsi:type="xsd:dateTime">2023-11-24T23:32:28</DataStatus></item></ArrayStatusTotal></item></ArrayEncomendaRetorno></item></ArrayLoteRetorno></ObterTrackingResponse></ns1:ObterTrackingResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>';
            $transferido_para = '<?xml version="1.0" encoding="ISO-8859-1"?><SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tns="http://edi.totalexpress.com.br/soap/webservice_v24.total"><SOAP-ENV:Body><ns1:ObterTrackingResponse xmlns:ns1="urn:ObterTracking"><ObterTrackingResponse xsi:type="tns:ObterTrackingResponse"><CodigoProc xsi:type="xsd:nonNegativeInteger">1</CodigoProc><ArrayLoteRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:LoteRetorno[1]"><item xsi:type="tns:LoteRetorno"><CodRetorno xsi:type="xsd:nonNegativeInteger">463613343</CodRetorno><DataGeracao xsi:type="xsd:date">2023-11-25</DataGeracao><ArrayEncomendaRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:EncomendaRetorno[1]"><item xsi:type="tns:EncomendaRetorno"><Awb xsi:type="xsd:string">TXAU452841984tx</Awb><Pedido xsi:type="xsd:string">pedido-59</Pedido><NotaFiscal xsi:type="xsd:nonNegativeInteger">14610</NotaFiscal><SerieNotaFiscal xsi:type="xsd:string">1</SerieNotaFiscal><IdCliente xsi:type="xsd:string"></IdCliente><CodigoObjeto xsi:type="xsd:string"></CodigoObjeto><ArrayStatusTotal xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:StatusTotal[1]"><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">102</CodStatus><DescStatus xsi:type="xsd:string">TRANSFERENCIA PARA: - SAO</DescStatus><DataStatus xsi:type="xsd:dateTime">2023-11-25T21:34:05</DataStatus></item></ArrayStatusTotal></item></ArrayEncomendaRetorno></item></ArrayLoteRetorno></ObterTrackingResponse></ns1:ObterTrackingResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>';

            // pedido-57
            $entregue = '<?xml version="1.0" encoding="ISO-8859-1"?><SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tns="http://edi.totalexpress.com.br/soap/webservice_v24.total"><SOAP-ENV:Body><ns1:ObterTrackingResponse xmlns:ns1="urn:ObterTracking"><ObterTrackingResponse xsi:type="tns:ObterTrackingResponse"><CodigoProc xsi:type="xsd:nonNegativeInteger">1</CodigoProc><ArrayLoteRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:LoteRetorno[2]"><item xsi:type="tns:LoteRetorno"><CodRetorno xsi:type="xsd:nonNegativeInteger">461343656</CodRetorno><DataGeracao xsi:type="xsd:date">2023-11-13</DataGeracao><ArrayEncomendaRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:EncomendaRetorno[1]"><item xsi:type="tns:EncomendaRetorno"><Awb xsi:type="xsd:string">TXAU425868668tx</Awb><Pedido xsi:type="xsd:string">pedido-57</Pedido><NotaFiscal xsi:type="xsd:nonNegativeInteger">14577</NotaFiscal><SerieNotaFiscal xsi:type="xsd:string">1</SerieNotaFiscal><IdCliente xsi:type="xsd:string"></IdCliente><CodigoObjeto xsi:type="xsd:string"></CodigoObjeto><ArrayStatusTotal xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:StatusTotal[1]"><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">104</CodStatus><DescStatus xsi:type="xsd:string">PROCESSO DE ENTREGA</DescStatus><DataStatus xsi:type="xsd:dateTime">2023-11-13T06:54:02</DataStatus></item></ArrayStatusTotal></item></ArrayEncomendaRetorno></item><item xsi:type="tns:LoteRetorno"><CodRetorno xsi:type="xsd:nonNegativeInteger">461362278</CodRetorno><DataGeracao xsi:type="xsd:date">2023-11-13</DataGeracao><ArrayEncomendaRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:EncomendaRetorno[1]"><item xsi:type="tns:EncomendaRetorno"><Awb xsi:type="xsd:string">TXAU425868668tx</Awb><Pedido xsi:type="xsd:string">pedido-57</Pedido><NotaFiscal xsi:type="xsd:nonNegativeInteger">14577</NotaFiscal><SerieNotaFiscal xsi:type="xsd:string">1</SerieNotaFiscal><IdCliente xsi:type="xsd:string"></IdCliente><CodigoObjeto xsi:type="xsd:string"></CodigoObjeto><ArrayStatusTotal xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:StatusTotal[1]"><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">1</CodStatus><DescStatus xsi:type="xsd:string">ENTREGA REALIZADA</DescStatus><DataStatus xsi:type="xsd:dateTime">2023-11-13T09:13:09</DataStatus></item></ArrayStatusTotal></item></ArrayEncomendaRetorno></item></ArrayLoteRetorno></ObterTrackingResponse></ns1:ObterTrackingResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>';
            
            // Nenhum pedido atribuido, somente para testes            
            $vazio = '<?xml version="1.0" encoding="ISO-8859-1"?><SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tns="http://edi.totalexpress.com.br/soap/webservice_v24.total"><SOAP-ENV:Body><ns1:ObterTrackingResponse xmlns:ns1="urn:ObterTracking"><ObterTrackingResponse xsi:type="tns:ObterTrackingResponse"><CodigoProc xsi:type="xsd:nonNegativeInteger">1</CodigoProc><ArrayLoteRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:LoteRetorno[0]"></ArrayLoteRetorno></ObterTrackingResponse></ns1:ObterTrackingResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>';
            $vazio2 = '<?xml version="1.0" encoding="ISO-8859-1"?><SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tns="http://edi.totalexpress.com.br/soap/webservice_v24.total"><SOAP-ENV:Body><ns1:ObterTrackingResponse xmlns:ns1="urn:ObterTracking"><ObterTrackingResponse xsi:type="tns:ObterTrackingResponse"><CodigoProc xsi:type="xsd:nonNegativeInteger">1</CodigoProc><ArrayLoteRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:LoteRetorno[0]"></ArrayLoteRetorno></ObterTrackingResponse></ns1:ObterTrackingResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>';
            $realizada_menos_5_minutos = '<?xml version="1.0" encoding="ISO-8859-1"?><SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tns="http://edi.totalexpress.com.br/soap/webservice_v24.total"><SOAP-ENV:Body><ns1:ObterTrackingResponse xmlns:ns1="urn:ObterTracking"><ObterTrackingResponse xsi:type="tns:ObterTrackingResponse"><CodigoProc xsi:type="xsd:nonNegativeInteger">5</CodigoProc><ArrayLoteRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:LoteRetorno[0]"></ArrayLoteRetorno></ObterTrackingResponse></ns1:ObterTrackingResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>';
            $vazio3 = '<?xml version="1.0" encoding="ISO-8859-1"?><SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tns="http://edi.totalexpress.com.br/soap/webservice_v24.total"><SOAP-ENV:Body><ns1:ObterTrackingResponse xmlns:ns1="urn:ObterTracking"><ObterTrackingResponse xsi:type="tns:ObterTrackingResponse"><CodigoProc xsi:type="xsd:nonNegativeInteger">1</CodigoProc><ArrayLoteRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:LoteRetorno[0]"></ArrayLoteRetorno></ObterTrackingResponse></ns1:ObterTrackingResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>';

            $agosto202429 = '<?xml version="1.0" encoding="ISO-8859-1"?><SOAP-ENV:Envelope SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:tns="http://edi.totalexpress.com.br/soap/webservice_v24.total"><SOAP-ENV:Body><ns1:ObterTrackingResponse xmlns:ns1="urn:ObterTracking"><ObterTrackingResponse xsi:type="tns:ObterTrackingResponse"><CodigoProc xsi:type="xsd:nonNegativeInteger">1</CodigoProc><ArrayLoteRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:LoteRetorno[8]"><item xsi:type="tns:LoteRetorno"><CodRetorno xsi:type="xsd:nonNegativeInteger">489250503</CodRetorno><DataGeracao xsi:type="xsd:date">2024-05-31</DataGeracao><ArrayEncomendaRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:EncomendaRetorno[1]"><item xsi:type="tns:EncomendaRetorno"><Awb xsi:type="xsd:string">TXAT118317577tx</Awb><Pedido xsi:type="xsd:string">83</Pedido><NotaFiscal xsi:type="xsd:nonNegativeInteger">14988</NotaFiscal><SerieNotaFiscal xsi:type="xsd:string">1</SerieNotaFiscal><IdCliente xsi:type="xsd:string"></IdCliente><CodigoObjeto xsi:type="xsd:string"></CodigoObjeto><ArrayStatusTotal xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:StatusTotal[1]"><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">0</CodStatus><DescStatus xsi:type="xsd:string">ARQUIVO RECEBIDO</DescStatus><DataStatus xsi:type="xsd:dateTime">2024-05-31T11:22:45</DataStatus></item></ArrayStatusTotal></item></ArrayEncomendaRetorno></item><item xsi:type="tns:LoteRetorno"><CodRetorno xsi:type="xsd:nonNegativeInteger">497166525</CodRetorno><DataGeracao xsi:type="xsd:date">2024-07-11</DataGeracao><ArrayEncomendaRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:EncomendaRetorno[1]"><item xsi:type="tns:EncomendaRetorno"><Awb xsi:type="xsd:string">TXAU646485532tx</Awb><Pedido xsi:type="xsd:string">pedido-79</Pedido><NotaFiscal xsi:type="xsd:nonNegativeInteger">14958</NotaFiscal><SerieNotaFiscal xsi:type="xsd:string">1</SerieNotaFiscal><IdCliente xsi:type="xsd:string"></IdCliente><CodigoObjeto xsi:type="xsd:string"></CodigoObjeto><ArrayStatusTotal xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:StatusTotal[1]"><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">142</CodStatus><DescStatus xsi:type="xsd:string">EXTRAVIO</DescStatus><DataStatus xsi:type="xsd:dateTime">2024-07-11T16:35:56</DataStatus></item></ArrayStatusTotal></item></ArrayEncomendaRetorno></item><item xsi:type="tns:LoteRetorno"><CodRetorno xsi:type="xsd:nonNegativeInteger">497886278</CodRetorno><DataGeracao xsi:type="xsd:date">2024-07-15</DataGeracao><ArrayEncomendaRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:EncomendaRetorno[2]"><item xsi:type="tns:EncomendaRetorno"><Awb xsi:type="xsd:string">TXAS152311734tx</Awb><Pedido xsi:type="xsd:string">pedido-96</Pedido><NotaFiscal xsi:type="xsd:nonNegativeInteger">14610</NotaFiscal><SerieNotaFiscal xsi:type="xsd:string">1</SerieNotaFiscal><IdCliente xsi:type="xsd:string"></IdCliente><CodigoObjeto xsi:type="xsd:string"></CodigoObjeto><ArrayStatusTotal xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:StatusTotal[1]"><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">0</CodStatus><DescStatus xsi:type="xsd:string">ARQUIVO RECEBIDO</DescStatus><DataStatus xsi:type="xsd:dateTime">2024-07-15T18:12:50</DataStatus></item></ArrayStatusTotal></item><item xsi:type="tns:EncomendaRetorno"><Awb xsi:type="xsd:string">TXAS152318341tx</Awb><Pedido xsi:type="xsd:string">pedido-97</Pedido><NotaFiscal xsi:type="xsd:nonNegativeInteger">14610</NotaFiscal><SerieNotaFiscal xsi:type="xsd:string">1</SerieNotaFiscal><IdCliente xsi:type="xsd:string"></IdCliente><CodigoObjeto xsi:type="xsd:string"></CodigoObjeto><ArrayStatusTotal xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:StatusTotal[1]"><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">0</CodStatus><DescStatus xsi:type="xsd:string">ARQUIVO RECEBIDO</DescStatus><DataStatus xsi:type="xsd:dateTime">2024-07-15T18:22:26</DataStatus></item></ArrayStatusTotal></item></ArrayEncomendaRetorno></item><item xsi:type="tns:LoteRetorno"><CodRetorno xsi:type="xsd:nonNegativeInteger">504388150</CodRetorno><DataGeracao xsi:type="xsd:date">2024-08-22</DataGeracao><ArrayEncomendaRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:EncomendaRetorno[1]"><item xsi:type="tns:EncomendaRetorno"><Awb xsi:type="xsd:string">TXAS200196400tx</Awb><Pedido xsi:type="xsd:string">pedido-96</Pedido><NotaFiscal xsi:type="xsd:nonNegativeInteger">14610</NotaFiscal><SerieNotaFiscal xsi:type="xsd:string">1</SerieNotaFiscal><IdCliente xsi:type="xsd:string"></IdCliente><CodigoObjeto xsi:type="xsd:string"></CodigoObjeto><ArrayStatusTotal xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:StatusTotal[1]"><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">0</CodStatus><DescStatus xsi:type="xsd:string">ARQUIVO RECEBIDO</DescStatus><DataStatus xsi:type="xsd:dateTime">2024-08-22T16:06:45</DataStatus></item></ArrayStatusTotal></item></ArrayEncomendaRetorno></item><item xsi:type="tns:LoteRetorno"><CodRetorno xsi:type="xsd:nonNegativeInteger">504400825</CodRetorno><DataGeracao xsi:type="xsd:date">2024-08-22</DataGeracao><ArrayEncomendaRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:EncomendaRetorno[1]"><item xsi:type="tns:EncomendaRetorno"><Awb xsi:type="xsd:string">TXAU646485532tx</Awb><Pedido xsi:type="xsd:string">pedido-79</Pedido><NotaFiscal xsi:type="xsd:nonNegativeInteger">14958</NotaFiscal><SerieNotaFiscal xsi:type="xsd:string">1</SerieNotaFiscal><IdCliente xsi:type="xsd:string"></IdCliente><CodigoObjeto xsi:type="xsd:string"></CodigoObjeto><ArrayStatusTotal xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:StatusTotal[1]"><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">10</CodStatus><DescStatus xsi:type="xsd:string">SINISTRO LIQUIDADO</DescStatus><DataStatus xsi:type="xsd:dateTime">2024-08-22T17:36:36</DataStatus></item></ArrayStatusTotal></item></ArrayEncomendaRetorno></item><item xsi:type="tns:LoteRetorno"><CodRetorno xsi:type="xsd:nonNegativeInteger">505436850</CodRetorno><DataGeracao xsi:type="xsd:date">2024-08-28</DataGeracao><ArrayEncomendaRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:EncomendaRetorno[1]"><item xsi:type="tns:EncomendaRetorno"><Awb xsi:type="xsd:string">TXAS206534513tx</Awb><Pedido xsi:type="xsd:string">pedido-100</Pedido><NotaFiscal xsi:type="xsd:nonNegativeInteger">15274</NotaFiscal><SerieNotaFiscal xsi:type="xsd:string">1</SerieNotaFiscal><IdCliente xsi:type="xsd:string"></IdCliente><CodigoObjeto xsi:type="xsd:string"></CodigoObjeto><ArrayStatusTotal xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:StatusTotal[1]"><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">0</CodStatus><DescStatus xsi:type="xsd:string">ARQUIVO RECEBIDO</DescStatus><DataStatus xsi:type="xsd:dateTime">2024-08-28T11:46:38</DataStatus></item></ArrayStatusTotal></item></ArrayEncomendaRetorno></item><item xsi:type="tns:LoteRetorno"><CodRetorno xsi:type="xsd:nonNegativeInteger">505483291</CodRetorno><DataGeracao xsi:type="xsd:date">2024-08-28</DataGeracao><ArrayEncomendaRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:EncomendaRetorno[1]"><item xsi:type="tns:EncomendaRetorno"><Awb xsi:type="xsd:string">TXAS206534513tx</Awb><Pedido xsi:type="xsd:string">pedido-100</Pedido><NotaFiscal xsi:type="xsd:nonNegativeInteger">15274</NotaFiscal><SerieNotaFiscal xsi:type="xsd:string">1</SerieNotaFiscal><IdCliente xsi:type="xsd:string"></IdCliente><CodigoObjeto xsi:type="xsd:string"></CodigoObjeto><ArrayStatusTotal xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:StatusTotal[2]"><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">97</CodStatus><DescStatus xsi:type="xsd:string">INICIO DE COLETA C EDI</DescStatus><DataStatus xsi:type="xsd:dateTime">2024-08-28T17:32:41</DataStatus></item><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">83</CodStatus><DescStatus xsi:type="xsd:string">COLETA REALIZADA</DescStatus><DataStatus xsi:type="xsd:dateTime">2024-08-28T17:32:41</DataStatus></item></ArrayStatusTotal></item></ArrayEncomendaRetorno></item><item xsi:type="tns:LoteRetorno"><CodRetorno xsi:type="xsd:nonNegativeInteger">505576175</CodRetorno><DataGeracao xsi:type="xsd:date">2024-08-29</DataGeracao><ArrayEncomendaRetorno xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:EncomendaRetorno[1]"><item xsi:type="tns:EncomendaRetorno"><Awb xsi:type="xsd:string">TXAS206534513tx</Awb><Pedido xsi:type="xsd:string">pedido-100</Pedido><NotaFiscal xsi:type="xsd:nonNegativeInteger">15274</NotaFiscal><SerieNotaFiscal xsi:type="xsd:string">1</SerieNotaFiscal><IdCliente xsi:type="xsd:string"></IdCliente><CodigoObjeto xsi:type="xsd:string"></CodigoObjeto><ArrayStatusTotal xsi:type="SOAP-ENC:Array" SOAP-ENC:arrayType="tns:StatusTotal[2]"><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">68</CodStatus><DescStatus xsi:type="xsd:string">COLETA RECEBIDA NO CD</DescStatus><DataStatus xsi:type="xsd:dateTime">2024-08-29T05:30:07</DataStatus></item><item xsi:type="tns:StatusTotal"><CodStatus xsi:type="xsd:nonNegativeInteger">101</CodStatus><DescStatus xsi:type="xsd:string">RECEBIDA E PROCESSADA NO CD - : PAG</DescStatus><DataStatus xsi:type="xsd:dateTime">2024-08-29T05:30:07</DataStatus></item></ArrayStatusTotal></item></ArrayEncomendaRetorno></item></ArrayLoteRetorno></ObterTrackingResponse></ns1:ObterTrackingResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>';

            $xml = str_ireplace(['SOAP-ENV:', 'SOAP:', 'ns1:'], '', $agosto202429);
            $doc = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOWARNING);

            $this->trackingTotalHistoric($doc);

            return Command::SUCCESS;
        }

        // $this->trackingTotal('2023-11-25'); // Data para consulta pontual
        $this->trackingTotal(); // Caso não informado, os serão transmitidos apenas os lotes ainda não enviados.

        return Command::SUCCESS;
    }

    private function trackingTotalHistoric($doc)
    {
        // $doc = $simplexml_load_string;

        $processar = false;
        switch ($doc->Body->ObterTrackingResponse->ObterTrackingResponse->CodigoProc) {
            case 0:
                // $this->line('0 – Cliente não autorizado a realizar o procedimento.');
                Log::error(static::class . ' linha ' . __LINE__ . ' 0 – Cliente não autorizado a realizar o procedimento.');
                break;
            case 1:
                $processar = true;
                Log::info(static::class . ' linha ' . __LINE__ . ' 1 – Processado com sucesso.');
                // $this->line('1 – Processado com sucesso.');
                break;
            case 2:
                // $this->line('2 – Sistema indisponível no momento. Tentar novamente mais tarde.');
                Log::error(static::class . ' linha ' . __LINE__ . ' 2 – Sistema indisponível no momento. Tentar novamente mais tarde.');
                break;
            case 3:
                // $this->line('3 – Erro na validação XSD. XML enviado incorretamente.');
                Log::error(static::class . ' linha ' . __LINE__ . ' 3 – Erro na validação XSD. XML enviado incorretamente.');
                break;
            case 4:
                // $this->line('4 – Erro sistêmico por parte da TOTAL EXPRESS. Tentar novamente mais tarde.');
                Log::error(static::class . ' linha ' . __LINE__ . ' 4 – Erro sistêmico por parte da TOTAL EXPRESS. Tentar novamente mais tarde.');
                break;
            case 5:
                // $this->line('5- Realizada solicitação em menos de 5 minutos.');
                Log::error(static::class . ' linha ' . __LINE__ . ' 5- Realizada solicitação em menos de 5 minutos.');
                break;
            default:
                // $this->line('Erro desconhecido.');
                Log::error(static::class . ' linha ' . __LINE__ . ' Erro desconhecido.');
                break;
        }

        if ($processar) {
            if ($doc->Body->ObterTrackingResponse->ObterTrackingResponse->ArrayLoteRetorno->item) {
                $order_events = [];
                foreach ($doc->Body->ObterTrackingResponse->ObterTrackingResponse->ArrayLoteRetorno->item as $item) {
                    foreach ($item->ArrayEncomendaRetorno->item as $subitem) {
                        $order = json_decode(json_encode($subitem->Pedido), TRUE)[0];
                        foreach ($subitem->ArrayStatusTotal->item as $status) {
                            $status_str = json_decode(json_encode($status->DescStatus), TRUE)[0];
                            $date_status = json_decode(json_encode($status->DataStatus), TRUE)[0];
                            $status_beauty = Str::ucfirst(Str::lower($status_str));

                            $order_db = Order::select('id')
                            ->where('id', substr($order, 7))
                            ->first();
                            
                            if($order_db) {
                                $order_db->update(['status' => $status_beauty]);
                                $order_events[] = [
                                    'order' => substr($order, 7),
                                    'status' => $status_beauty,
                                    'created_at' => Carbon::parse($date_status)
                                ];

                                $delivery = Delivery::select()
                                ->where('order', substr($order, 7))
                                ->first();
    
                                if($delivery) {
                                    // Verificar se a mercadoria foi entregue para remover a notification true do deliveries
                                    $delivery->update(['status' => $status_beauty]);
                                } else {
                                    Delivery::create([
                                        'order' => substr($order, 7),
                                        'method' => 'Transportadora',
                                        'status' => $status_beauty,
                                    ]);
                                }
                            }
                        }
                    }
                }
                OrderEvent::insert($order_events);
                Log::info(static::class . ' linha ' . __LINE__ . ' Atualizado status dos pedidos.');
            } else {
                // $this->line('Nenhum item trasmitido.');
                Log::info(static::class . ' linha ' . __LINE__ . ' Nenhum item transmitido.');

            }
        }
    }

    private function trackingTotal($date = null)
    {
        // Com data para consulta pontual
        if ($date) {
            $xml = '<soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:ObterTracking"><soapenv:Header/><soapenv:Body><urn:ObterTracking soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><ObterTrackingRequest xsi:type="web:ObterTrackingRequest" xmlns:web="http://edi.totalexpress.com.br/soap/webservice_v24.total"><DataConsulta xsi:type="xsd:date">' . $date . '</DataConsulta></ObterTrackingRequest></urn:ObterTracking></soapenv:Body></soapenv:Envelope>';
        } else {
            // Caso não informado, os serão transmitidos apenas os lotes ainda não enviados.
            $xml = '<soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:urn="urn:ObterTracking"><soapenv:Header/><soapenv:Body><urn:ObterTracking soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><ObterTrackingRequest xsi:type="web:ObterTrackingRequest" xmlns:web="http://edi.totalexpress.com.br/soap/webservice_v24.total"></ObterTrackingRequest></urn:ObterTracking></soapenv:Body></soapenv:Envelope>';
        }

        // $date = '2023-11-13';
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

            if (isset($doc->Body->ObterTrackingResponse->ObterTrackingResponse->CodigoProc)) {
                $this->trackingTotalHistoric($doc);
            } else {
                Log::error(static::class . ' linha ' . __LINE__ . ' Algum erro na resposta (Tracking Total).');
            }
        }
    }
}
