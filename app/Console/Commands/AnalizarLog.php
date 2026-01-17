<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\OrderEvent;
use Illuminate\Console\Command;

class AnalizarLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analizar:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processar logs';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $xml = '<?xml version="1.0" encoding="ISO-8859-1" standalone="yes"?><transaction><date>2024-02-27T09:50:19.000-03:00</date><code>850FDB40-7584-4D06-9927-5F0CA83BD9D6</code><reference>63</reference><type>1</type><status>4</status><lastEventDate>2024-02-27T09:50:51.000-03:00</lastEventDate><paymentMethod><type>11</type><code>402</code></paymentMethod><pix><pixDate>2024-02-27T09:50:19.000-03:00</pixDate><holderName>MIGUEL ANGELO DA COSTA QUINTANA</holderName><personType>PF</personType><bankName>BCO DO BRASIL S.A.</bankName><bankAgency>2950</bankAgency><bankAccount>206784</bankAccount><bankAccountType>CC</bankAccountType></pix><grossAmount>2890.00</grossAmount><discountAmount>0.00</discountAmount><creditorFees><intermediationRateAmount>0.00</intermediationRateAmount><intermediationFeeAmount>54.62</intermediationFeeAmount></creditorFees><netAmount>2835.38</netAmount><extraAmount>0.00</extraAmount><escrowEndDate>2024-02-27T09:50:19.000-03:00</escrowEndDate><installmentCount>1</installmentCount><itemCount>1</itemCount><items><item><id>AUTO_ID 1</id><description>outros</description><quantity>1</quantity><amount>2890.00</amount></item></items><sender><name>Maria Margareth Bainy</name><email>margarethbainy@gmail.com</email><phone><areaCode>53</areaCode><number>999816841</number></phone></sender><shipping><address><street>SENADOR MENDONCA 1 BL B</street><number>101</number><complement>T�rreo (muro vidro)</complement><district>Centro</district><city>PELOTAS</city><state>RS</state><country>BRA</country><postalCode>96015200</postalCode></address><type>3</type><cost>0.00</cost></shipping><primaryReceiver><publicKey>PUB2452826528C245D585B9488EF2CD2550</publicKey></primaryReceiver><liquidation /></transaction>';
        $xml_deco = simplexml_load_string($xml);
        
        $json_str = json_encode($xml_deco);
        $json = json_decode($json_str);
        
        $this->line($json->type);
        $this->line($json->paymentMethod->type);
        return Command::SUCCESS;

        // type = 1- Pagamento: a transação foi criada por um comprador fazendo um pagamento.
        // paymentMethod = 11	PIX: o comprador escolheu pagar a transação utilizando o PIX.

        $status = [
            1 => "Aguardando pagamento",
            2 => "Em análise",
            3 => "Paga",
            4 => "Disponível",
            5 => "Em disputa",
            6 => "Devolvida",
            7 => "Cancelada",
            8 => "Debitado",
            9 => "Retenção temporária",
        ];

        $this->line($status[$json->status]);

        $update = Order::where('id', $json->reference)->update(['status' => $status[$json->status]]);
        
        if($update) {
            $this->line('Atualizado ordem (Analizar Log): ' . $json->reference);
            if(OrderEvent::create(['order' => $json->reference, 'status' => $status[$json->status]])) {
                $this->line('Criado evento (Analizar Log)');
            }
        }

        return Command::SUCCESS;
    }
}
