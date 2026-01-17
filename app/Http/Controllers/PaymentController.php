<?php

namespace App\Http\Controllers;

use App\Helpers\CouponHelper;
use App\Jobs\PrepareOrderAdm;
use App\Models\Complete;
use App\Models\Order;
use App\Models\OrderEvent;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;
use App\Models\SafraPedidos;
use Carbon\Carbon;

class PaymentController extends Controller
{
    private $validation_credit_safra = [
        'merchantChargeId' => 'required',
        'temporaryCardToken' => 'required',
        'installmentNumber' => 'required|between:1,12',
    ];

    private $validation_credit_safra_err = [
        'merchantChargeId' => 'Nenhum pedido selecionado, tente novamente',
        'temporaryCardToken' => 'Falha no cartão, tenten novamente',
        'installmentNumber' => 'Falha na seleção de parcelas, tente novamente',
    ];

    private $validation_pix_safra = [
        'merchantChargeId' => 'required',
        'holder-name' => 'required',
        'holder-doc' => 'required',
    ];

    private $validation_pix_safra_err = [
        'merchantChargeId' => 'Nenhum pedido selecionado, tente novamente.',
        'holder-name' => 'Nome é necessário.',
        'holder-doc' => 'Um documento é necessário.',
    ];

    /**
     * Carrega uma view para pagamento no cartão de credito pagarme
     */
    public function payCreditPagarme($order)
    {
        $db_order = Order::select('orders.id', 'orders.coupon', 'orders.products', 'payments.type')
            ->join('payments', 'payments.id', 'orders.id')
            ->where('orders.id', $order)
            ->first();

        $amount_complete = CouponHelper::instance()->get_amount($db_order->coupon, json_decode($db_order->products), false, 'Credit');

        // dd($amount_complete);

        return view('pagarme.credit', compact('db_order', 'amount_complete'));
    }

    /**
     * Carrega uma view para pagamento no cartão de crédito
     * 
     * @param int $order Número do pedido
     * @return \Illuminate\View\View
     */
    public function payCreditSafra($order)
    {
        $search = $this->searchData($order);

        $finded_order = $search['order'];

        // verficiar cupom antes de retornar no amount
        $coupon_details = CouponHelper::instance()->get_amount($finded_order->coupon, json_decode($finded_order->products), false, 'Credit');

        $amount = $coupon_details['amount'];

        return view('safrapay.credit', compact('finded_order', 'amount'));
    }

    /**
     * Carrega uma view para pagamento com pix
     * 
     * @param int $order Número do pedido
     * @return \Illuminate\View\View
     */
    public function payPixSafra($order)
    {
        $search = $this->searchData($order);

        if ($search) {
            if ($search['order']->status == 'Authorized') {
                return redirect(route('orders.show_order', $order));
            }
        }

        $safra_pedido = SafraPedidos::select(
            'order',
            'qrCode',
            'qrCodeImage',
            'creationDateTime',
            'amount'
        )->where('order', $order)
            ->first();


        if ($safra_pedido) {
            $expiration = Carbon::parse($safra_pedido->creationDateTime);
            $expiration->addMinutes(30);

            if ($expiration->lessThan(Carbon::now())) {
                return redirect(route('safra.pix.remove', $order));
            }

            return view('safrapay.pix-pagar', compact('safra_pedido'));
        } else {
            $finded_order = $search['order'];
            // $amount = $search['amount'];

            // verficiar cupom antes de retornar no amount
            $coupon_details = CouponHelper::instance()->get_amount($finded_order->coupon, json_decode($finded_order->products), false, 'PIX');

            $amount = $coupon_details['amount'];

            return view('safrapay.pix', compact('finded_order', 'amount'));
        }
    }

    /**
     * Remove o pix da ordem e volta para novo pix
     * Permite gerar novo pix
     * route name safra.pix.reload
     * 
     * @param int $order Número do pedido
     * @return \Illuminate\Http\RedirectResponse
     */
    public function gerNewCodePix($order)
    {
        $this->remPixPayI($order);
        return redirect(route('safra.pix', $order));
    }

    /**
     * Remove o pix da ordem e volta para o pedido
     * Permite escolher pix ou crédito
     * 
     * @param int $order Número do pedido
     * @return \Illuminate\View\View
     */
    public function remPixPay($order)
    {
        $this->remPixPayI($order);
        return redirect(route('orders.show_order', $order));
    }

    /**
     * Remove o cartao de credito da ordem e volta para o pedido
     * Permite escolher pix ou crédito
     * 
     * @param int $order Número do pedido
     * @return \Illuminate\View\View
     */
    public function remCreditPay($order)
    {
        $this->remPixPayI($order);
        return redirect(route('orders.show_order', $order));
    }

    /**
     * Altera o pedido para waiting_pay
     * E move o pedido de safra pedidos para a lixeira
     * 
     * @param int $order Número do pedido
     * @return null
     */
    private function remPixPayI($order)
    {
        $order_data = $this->searchData($order);

        // atualizar pedido para waiting e tipo de pagamento remover
        $order_data['order']->update([
            'status' => 'waiting_pay',
            'type' => ''
        ]);

        // Atualizar payment
        Payment::select('id')
            ->where('id', $order_data['order']->payment)
            ->update([
                'type' => '',
                'installments' => ''
            ]);

        // remover safra pedidos
        $safra_pedidos = SafraPedidos::where('order', $order)->first();
        if ($safra_pedidos) {
            $safra_pedidos->delete();
        }

        // Criar evento del_pay_type
        OrderEvent::create([
            'order' => $order,
            'status' => 'del_pay_type'
        ]);
    }

    /**
     * Gera um QR Code após realizar as validações
     * 
     * @param \Illuminate\Http\Request $request Os dados da requisição HTTP.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processPixSafra(Request $request)
    {
        // Validar dados vindos dos inputs
        $request->validate($this->validation_pix_safra, $this->validation_pix_safra_err);

        // Recupera ordem
        $order = $this->getOrderSafra($request->merchantChargeId);

        // Verificar se não foi deletado deleted_at
        if ($order->deleted_at) {
            return back()->withInput()->with('error', 'Pedido deletado.');
        }

        // Verificar o status waiting_pay - ver outros status possíveis
        if ($order->status == 'waiting_pay' || $order->status == 'NotAuthorized') {
        } else {
            return back()->withInput()->with('error', 'Status do pedido não permite o pagamento.');
        }

        $current_user = $this->getAndValidateUser($order);

        // Lidar com cupons
        if ($order->coupon) {
            $products_order = json_decode($order->products);
            $amount = CouponHelper::instance()->get_amount($order->coupon, $products_order)['amount'];
        } else {
            $amount = $order->amount;
        }

        // Montagem de payload
        $payload = $this->makePayloadSafraPix([
            'merchantChargeId' => $request->merchantChargeId,
            'amount' => $amount,
            'doc' => $current_user[1]
        ], $current_user[0]);

        $token = $this->getTokenSafra();
        if (!$token) {
            return back()->withInput()->with('error', 'Sentimos muito, seu pagamento não pôde ser processado devido a um erro técnico. Contate o administrador.');
        }

        // Enviar payload para processar em um CURL
        $payment = $this->sendPixSafra($token, json_encode($payload), $order->id);

        $this->processResSafraPix(json_decode($payment));

        return redirect(route('safra.pix', $order->id))->with('success', 'Gerado QR Code para pagamento.');
        // dd($res_processed);
    }

    public function testeTk()
    {
        // $token = $this->getTokenSafra();
        // dd($token);

        Log::info(static::class . ' linha ' . __LINE__ . ' teste curl');

        ob_start();
        $out = fopen('php://output', 'w');

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt($curl, CURLOPT_STDERR, $out);

        curl_setopt($curl, CURLOPT_POST, 1);

        curl_setopt($curl, CURLOPT_URL, "https://payment-hml.safrapay.com.br/v2/merchant/auth");

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'authorization: mk_itfYBP6xB5SAnIm5Haq60vYnNd',
        ]);

        $response = curl_exec($curl);

        fclose($out);
        $debug = ob_get_clean();

        Log::info(static::class . ' linha ' . __LINE__ . ' teste curl ' . $debug);

        curl_close($curl);


        dd($response);
    }

    public function processCreditSafra(Request $request)
    {
        // Validar dados vindos dos inputs
        $request->validate($this->validation_credit_safra, $this->validation_credit_safra_err);

        // Recupera ordem
        $order = $this->getOrderSafra($request->merchantChargeId);

        // Verificar se não foi deletado deleted_at
        if ($order->deleted_at) {
            return back()->withInput()->with('error', 'Pedido deletado.');
        }
        // Verificar o status waiting_pay - ver outros status possíveis
        if ($order->status == 'waiting_pay' || $order->status == 'NotAuthorized') {
        } else {
            return back()->withInput()->with('error', 'Status do pedido não permite o pagamento.');
        }

        $current_user = $this->getAndValidateUser($order);

        // Lidar com cupons
        if ($order->coupon) {
            $products_order = json_decode($order->products);
            $amount = CouponHelper::instance()->get_amount($order->coupon, $products_order)['amount'];
        } else {
            $amount = $order->amount;
        }

        // Lidar com o total visto as parcelas escolhidas
        $valor_prazo = $this->installmentAmount($amount, $request->installmentNumber);
        Log::info(__CLASS__ . ' linha ' . __LINE__ . ' amount ' . $amount);
        Log::info(__CLASS__ . ' linha ' . __LINE__ . ' valor a prazo ' . $valor_prazo);
        $amount = number_format($valor_prazo, 2, '.', '');

        $order->update([
            'amount' => $amount,
        ]);

        $complete = $this->getCompleteSafra($current_user[0]->id);

        // Montagem de payload
        $payload = $this->makePayloadSafraCredit([
            'merchantChargeId' => $request->merchantChargeId,
            'temporaryCardToken' => $request->temporaryCardToken,
            'installmentNumber' => $request->installmentNumber,
            'amount' => $amount,
            'doc' => $current_user[1]
        ], $current_user[0], $complete);

        $token = $this->getTokenSafra();
        if (!$token) {
            return back()->withInput()->with('error', 'Sentimos muito, seu pagamento não pôde ser processado devido a um erro técnico. Contate o administrador.');
        }

        // Enviar payload para processar em um CURL
        $payment = $this->sendCreditSafra($token, json_encode($payload), $order->id);

        if (!json_validate($payment)) {
            Log::error(__CLASS__ . ' linha ' . __LINE__ . ' Falha na formatação do json de resposta Safra');
            Log::error(__CLASS__ . ' linha ' . __LINE__ . ' ' . $payment);
            return back()->withInput()->with('error', 'Sentimos muito, seu pagamento falhou devido a uma falha técnica. Entre em contato com o administrador.');
        }

        $res_processed = $this->processResSafraCredit(json_decode($payment), $amount);

        // Tratar erros de pagamento
        if (!$res_processed) {
            return back()->withInput()->with('error', 'Sentimos muito, seu pagamento falhou devido a uma falha técnica. Entre em contato com o administrador.');
        }

        if (!$res_processed['status']) {
            return back()->withInput()->with('error', 'Sentimos muito, seu pagamento falhou. Verifique seus dados e tente novamente.');
        }

        if ($res_processed['transaction_status'] == 'Captured' && $res_processed['charge_status'] == 'Authorized') {
            // Disparar e-email
            $order_email = Order::select(
                'user',
                'id',
                'products',
                'coupon',
            )
                ->where('id', $order->id)
                ->first();

            PrepareOrderAdm::dispatch($order_email);

            return redirect(route('orders.show_order', $order->id))->with('success', '🙂 Pagamento realizado com sucesso.');
        } else {
            return back()->withInput()->with('success', 'Aguardando resposta do emissor do seu cartão. Se o status do seu pedido não mudar em breve, entre em contato conosco.');
        }
    }

    private function searchData($order)
    {
        $finded_order = Order::select('id', 'user', 'amount', 'coupon', 'products', 'status', 'payment')->find($order);

        // // Buscar valor a pagar calculando cupom, se possuir
        // if ($finded_order->coupon) {
        //     $coupon_details = CouponHelper::instance()->get_amount($finded_order->coupon, json_decode($finded_order->products), false, 'PIX');

        //     $amount = $coupon_details['amount'];
        // } else {
        $amount = $finded_order->amount;
        // }

        if (!$finded_order) {
            return back()->withInput()->with('error', 'Pedido não encontrado.');
        }

        if ($finded_order->user != auth()->user()->id) {
            return back()->withInput()->with('error', 'Pedido não pertece ao seu usuário.');
        }

        return [
            'order' => $finded_order,
            'amount' => $amount
        ];
    }

    private function getTokenSafra()
    {
        Log::info(static::class . ' linha ' . __LINE__ . ' SAFRA_DEBUG ' . env('SAFRA_DEBUG'));

        if (env('SAFRA_DEBUG')) {
            ob_start();
            $out = fopen('php://output', 'w');
        }

        // Produção
        // $curl = curl_init('https://payment.safrapay.com.br/v2/merchant/auth');
        // Sandbox
        // $curl = curl_init('https://payment-hml.safrapay.com.br/v2/merchant/auth');
        // ENV
        $curl = curl_init(env('SAFRA_URL_TOKEN'));

        if (!$curl) {
            Log::error(static::class . ' linha ' . __LINE__ . ' URL autenticação Safra inválida...');
            return false;
        } else {
            Log::info(static::class . ' linha ' . __LINE__ . ' URL autenticação Safra (SAFRA_URL_TOKEN) ' . env('SAFRA_URL_TOKEN'));
            Log::info(static::class . ' linha ' . __LINE__ . ' SAFRA_MK ' . env('SAFRA_MK'));
        }

        if (env('SAFRA_DEBUG')) {
            curl_setopt($curl, CURLOPT_VERBOSE, true);
            curl_setopt($curl, CURLOPT_STDERR, $out);
        }

        // Baixa segurança apenas para testes
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        Log::info(static::class . ' linha ' . __LINE__ . ' Montagem header Authorization: ' . 'Authorization: ' . env('SAFRA_MK'));

        // Sandbox
        // curl_setopt($curl, CURLOPT_HTTPHEADER, [
        //     'authorization: mk_itfYBP6xB5SAnIm5Haq60vYnNd',
        // ]);
        // Produção
        // curl_setopt($curl, CURLOPT_HTTPHEADER, [
        //     'authorization: mk_qDoiRfsNzOrUwGt9ftORfLyxU4',
        // ]);
        // ENV
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'authorization: ' . env('SAFRA_MK'),
        ]);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        Log::info(static::class . ' linha ' . __LINE__ . ' Iniciando autenticação Safra...');

        $response = curl_exec($curl);

        if (env('SAFRA_DEBUG')) {
            fclose($out);
            $debug = ob_get_clean();

            Log::info(static::class . ' linha ' . __LINE__ . " Debug da autenticação: " . $debug);
        }

        $err = curl_error($curl);

        curl_close($curl);

        Log::info(static::class . ' linha ' . __LINE__ . " Autenticação - curl_getinfo: " . curl_getinfo($curl, CURLINFO_HTTP_CODE));

        if ($err || curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
            Log::error(static::class . ' linha ' . __LINE__ . " Autenticação - curl_error:" . curl_error($curl));
            return false;
        } else {
            Log::info(static::class . ' linha ' . __LINE__ . " Autenticação - Response: " . $response);
            return json_decode($response)->accessToken;
        }
    }

    private function sendCreditSafra($token, $payload, $order_id)
    {
        Log::info(static::class . ' linha ' . __LINE__ . ' Payload (' . $order_id . '): ' . $payload);

        if (env('SAFRA_DEBUG')) {
            ob_start();
            $out = fopen('php://output', 'w');
        }

        $curl = curl_init(env('SAFRA_URL_CREDIT'));

        if (env('SAFRA_DEBUG')) {
            curl_setopt($curl, CURLOPT_VERBOSE, true);
            curl_setopt($curl, CURLOPT_STDERR, $out);
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ]);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);

        Log::info(static::class . ' linha ' . __LINE__ . ' Iniciando transmissão de dados pedido ' . $order_id . ' ...');

        $response = curl_exec($curl);

        if (env('SAFRA_DEBUG')) {
            fclose($out);
            $debug = ob_get_clean();

            Log::info(static::class . ' linha ' . __LINE__ . " Pedido (" . $order_id . ") - Debug: " . $debug);
        }

        $err = curl_error($curl);

        curl_close($curl);

        Log::info(static::class . ' linha ' . __LINE__ . " Pedido (" . $order_id . ") - curl_getinfo: " . curl_getinfo($curl, CURLINFO_HTTP_CODE));

        if ($err) {
            Log::error(static::class . ' linha ' . __LINE__ . " Pedido (" . $order_id . ") - curl_error:" . curl_error($curl));
            return false;
        } else {
            Log::info(static::class . ' linha ' . __LINE__ . " Pedido (" . $order_id . ") - Response: " . $response);
            return $response;
        }

        Log::info(static::class . ' linha ' . __LINE__ . " Pedido (" . $order_id . "): finalizado envio de pagamento cartão de crédito Safra com ou sem erros.");
    }

    private function sendPixSafra($token, $payload, $order_id)
    {
        Log::info(static::class . ' linha ' . __LINE__ . ' Payload PIX (' . $order_id . '): ' . $payload);

        if (env('SAFRA_DEBUG')) {
            ob_start();
            $out = fopen('php://output', 'w');
        }

        $curl = curl_init();

        if (env('SAFRA_DEBUG')) {
            curl_setopt($curl, CURLOPT_VERBOSE, true);
            curl_setopt($curl, CURLOPT_STDERR, $out);
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ]);

        curl_setopt($curl, CURLOPT_URL, env('SAFRA_URL_PIX'));

        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);

        Log::info(static::class . ' linha ' . __LINE__ . ' Iniciando transmissão de dados para gerar PIX ao pedido ' . $order_id . ' ...');

        $response = curl_exec($curl);

        if (env('SAFRA_DEBUG')) {
            fclose($out);
            $debug = ob_get_clean();

            Log::info(static::class . ' linha ' . __LINE__ . " Pedido (" . $order_id . ") - Debug: " . $debug);
        }

        $err = curl_error($curl);

        curl_close($curl);

        Log::info(static::class . ' linha ' . __LINE__ . " Pedido (" . $order_id . ") - curl_getinfo: " . curl_getinfo($curl, CURLINFO_HTTP_CODE));

        if ($err) {
            Log::error(static::class . ' linha ' . __LINE__ . " Pedido (" . $order_id . ") - curl_error:" . curl_error($curl));
            return false;
        } else {
            Log::info(static::class . ' linha ' . __LINE__ . " Pedido (" . $order_id . ") - Response: " . $response);
            return $response;
        }

        Log::info(static::class . ' linha ' . __LINE__ . " Pedido (" . $order_id . "): finalizado envio de pagamento PIX Safra com ou sem erros.");
    }

    /**
     * Validação do retorno do envio do payload para a Safra
     * 
     * @param object $response
     * @param float $amount
     * @return array|false
     */
    private function processResSafraCredit($response, $amount)
    {
        // Valida a requisição em si a estruturação ou erro de conexão com a Safra
        if ($response->success == false) {
            Log::error(__CLASS__ . ' linha ' . __LINE__ . ' Falha de conexão com a Safra');
            return false;
        }

        // Validar pagamento
        $return = [
            'transaction_status' => $response->charge->transactions[0]->transactionStatus,
            'transaction_error_message' => $response->charge->transactions[0]->errorMessage,
            'charge_status' => $response->charge->chargeStatus
        ];

        if ($response->charge->chargeStatus == 'Authorized') {
            $return['status'] = true;
        } else {
            $return['transaction_error_code'] = $response->charge->transactions[0]->errorCode;
            $return['status'] = false;
        }

        // Criar pagamento na tabela de pagamentos
        $payment = $this->payAddUpCredit($response);

        // Criar order event
        // Ver respostas, status do safra, senão utilizar os existentes ou até converter
        OrderEvent::create(['order' => $response->charge->merchantChargeId, 'status' => $response->charge->chargeStatus]);

        // Atualizar pedido informações, status, etc
        $order = Order::where('id', $response->charge->merchantChargeId)
            ->first();

        if ($order) {
            $order_up = [
                'payment' => $payment->id,
            ];

            $charge_amount = $response->charge->transactions[0]->amount;
            $amount_in_cents = $amount * 100;

            if ((int) $charge_amount == (int) $amount_in_cents) {
                $order_up['status'] = $response->charge->chargeStatus;
            } else {
                // Preciso verificar se o parcelamento aumentou o valor
                $order_up['status'] = 'divergencia_valores';
            }

            $order->update($order_up);
        } else {
            $return['error_message'] = 'Não foi possível encontrar a ordem: ' . $response->charge->merchantChargeId;
            $return['status'] = false;
            Log::info(__CLASS__ . ' linha ' . __LINE__ . ' ' . $return);
            return $return;
        }

        // atualizar safra_pedidos
        $safra_pedidos = SafraPedidos::where('order', $order->id)->first();

        $safra_pedidos_data = [
            'charge_id' => $response->charge->id,
            'paymentType' => 'Credit',
            'amount' => $response->charge->transactions[0]->amount,
            'acquirer' => $response->charge->transactions[0]->acquirer,
            'creationDateTime' => $response->charge->transactions[0]->creationDateTime,
            'nsu' => $response->charge->nsu,
        ];

        if ($safra_pedidos) {
            $safra_pedidos->update($safra_pedidos_data);
        } else {
            $safra_pedidos_data['order'] = $order->id;
            SafraPedidos::create($safra_pedidos_data);
        }

        Log::info(__CLASS__ . ' linha ' . __LINE__ . ' ' . json_encode($return));

        return $return;
    }

    private function processResSafraPix($response)
    {
        // Valida a requisição em si a estruturação ou erro de conexão com a Safra
        if ($response->success == false || !isset($response->success)) {
            return false;
        }

        // Validar pagamento
        $return = [
            'transaction_status' => $response->charge->transactions[0]->transactionStatus,
            'charge_status' => $response->charge->chargeStatus
        ];

        if ($response->charge->chargeStatus == 'PreAuthorized') {
            $return['status'] = true;
        } else {
            $return['status'] = false;
        }

        // Criar pagamento nas tabelas
        // Vou editar o retorno do payAddUpPix aí terá que mudar o uso dela aqui
        $payment = $this->payAddUpPix($response);

        // Criar order event
        // Ver respostas, status do safra, senão utilizar os existentes ou até converter
        OrderEvent::create(['order' => $response->charge->merchantChargeId, 'status' => $response->charge->chargeStatus]);

        // Atualizar pedido informações, status, etc
        $order = Order::where('id', $response->charge->merchantChargeId)
            ->first();

        if ($order) {
            $order_up = ['payment' => $payment['payment']->id];

            // Em produção descomentar isso
            // Já que safra sempre retorna o mesmo valor
            // if ($response->charge->transactions[0]->amount == $order->amount) {
            $order_up['status'] = $response->charge->chargeStatus;

            $order->update($order_up);
        } else {
            $return['error_message'] = 'Não foi possível encontrar a ordem: ' . $response->charge->merchantChargeId;
            $return['status'] = false;
            return $return;
        }

        return $return;
    }

    private function payAddUpCredit($response)
    {
        $payment = Order::select(
            'payments.id',
        )->leftJoin('payments', 'payments.id', '=', 'orders.payment')
            ->where('orders.id', $response->charge->merchantChargeId)
            ->first();

        // $payment = Payment::where('id', $response->charge->merchantChargeId)
        //     ->first();

        $data = [
            'pay_id' => $response->charge->id,
            'amount' => $response->charge->transactions[0]->amount,
            'type' => $response->charge->transactions[0]->paymentType,
            'installments' => $response->charge->transactions[0]->installmentNumber,
            'cd_brand' => $response->charge->transactions[0]->card->brand,
            'cd_first_digits' => substr($response->charge->transactions[0]->card->cardNumber, 0, 6),
            'cd_last_digits' => substr($response->charge->transactions[0]->card->cardNumber, -4, 4),
            'cd_exp_month' => $response->charge->transactions[0]->card->expirationMonth,
            'cd_exp_year' => $response->charge->transactions[0]->card->expirationYear,
            'cd_holder_name' => $response->charge->transactions[0]->card->cardholderName,
        ];

        if ($payment) {
            $payment_arm = Payment::where('id', $payment->id)->first();
            $payment_arm->update($data);
            return $payment_arm;
        }

        $payment_new = Payment::create($data);
        return $payment_new;
    }

    private function gerASaveQr($qrcode, $order_id)
    {
        $image = str_replace('data:image/png;base64,', '', $qrcode);
        $image = str_replace(' ', '+', $image);

        $path = 'qr_code/pix_order_' . $order_id . '.png';

        $i = 1;
        while (Storage::fileExists($path)) {
            $path = 'qr_code/pix_order_' . $order_id . '-' . $i . '.png';
            $i++;
        }

        Storage::disk('public')->put($path, base64_decode($image));

        return $path;
    }

    private function payAddUpPix($response)
    {
        // Criei uma nova tabela safra pedido para armazenar outros valores
        // Terei que atualizar a payment e esta nova tabela
        $payment_data = [
            'pay_id' => $response->charge->id,
            'amount' => $response->charge->transactions[0]->amount,
            'type' => $response->charge->transactions[0]->paymentType,
        ];

        $payment = Payment::where('id', $response->charge->merchantChargeId)
            ->first();

        $return = [];

        if ($payment) {
            $payment->update($payment_data);
            $return['payment'] = $payment;
        } else {
            $payment_new = Payment::create($payment_data);
            $return['payment'] = $payment;
        }

        $qrCodeImage = $this->gerASaveQr($response->charge->transactions[0]->qrCodeBase64, $response->charge->merchantChargeId);

        $safra_pedido = [
            'order' => $response->charge->merchantChargeId,
            'charge_id' => $response->charge->id,
            'aditumNumber' => $response->charge->transactions[0]->aditumNumber,
            'qrCode' => $response->charge->transactions[0]->qrCode,
            'qrCodeImage' => $qrCodeImage, // Gerar a imagem, savar e adicionar aqui a url
            'transactionId' => $response->charge->transactions[0]->transactionId,
            'paymentType' => $response->charge->transactions[0]->paymentType,
            'amount' => $response->charge->transactions[0]->amount,
            'acquirer' => $response->charge->transactions[0]->acquirer,
            'creationDateTime' => Carbon::parse($response->charge->transactions[0]->creationDateTime),
            'nsu' => $response->charge->nsu
        ];

        $payment_safra = SafraPedidos::select('id')
            ->where('order', $response->charge->merchantChargeId)
            ->first();

        if ($payment_safra) {
            // $payment_safra->update($safra_pedido);

            SafraPedidos::where('id', $payment_safra)
                ->first()->update($safra_pedido);

            $return['safra_pedidos'] = $payment_safra;
        } else {
            $payment_safra_new = SafraPedidos::create($safra_pedido);
            $return['safra_pedidos'] = $payment_safra_new;
        }

        return $return;
    }

    private function installmentAmount($amount, $installments)
    {
        $installment_tax = json_decode('{"1":0,"2":0,"3":0,"4":0,"5":0,"6":0,"7":12.72,"8":13.81,"9":14.9,"10":15.99,"11":17.08,"12":18.17}');

        // Log::info(__CLASS__ . ' linha ' . __LINE__ . ' ' . $installment_tax->$installments);
        // Log::info(__CLASS__ . ' linha ' . __LINE__ . ' ' . $amount);

        $juros = ($installment_tax->$installments / 100) * $amount;

        return $amount + $juros;
    }

    private function getAndValidateUser($order)
    {
        $current_user = auth()->user();

        if ($order->user != $current_user->id) {
            return back()->withInput()->with('error', 'Pedido não pertece ao seu usuário.');
        }

        // Validar se tem CNPJ/CPF válidos
        $doc = preg_replace('/\D/', '', $current_user->doc);

        if ($doc == '' || strlen($doc) < 11) {
            return back()->withInput()->with('error', 'Documento incorreto, verifique o documento informado.');
        } elseif (strlen($doc) < 16 && strlen($doc) > 11) {
            return back()->withInput()->with('error', 'Documento incorreto, verifique o documento informado.');
        }

        return [$current_user, $doc];
    }

    private function getOrderSafra($order_id)
    {
        return Order::select(
            'orders.id',
            'orders.user',
            'orders.products',
            'orders.amount',
            'orders.status',
            'orders.coupon',
            'orders.payment',
            'orders.deleted_at',
        )
            ->where('id', $order_id)
            ->first();
    }

    private function getCompleteSafra($user_id)
    {
        return Complete::select(
            'completes.street',
            'completes.number',
            'completes.locality',
            'completes.city',
            'completes.region_code',
            'completes.country',
            'completes.postal_code',
            'completes.complement',
        )
            ->where('user', $user_id)
            ->first();
    }

    private function makePayloadSafraCredit($data, $current_user, $complete)
    {
        $payload = [
            "charge" => [
                "merchantChargeId" => $data['merchantChargeId'],
                "customer" => [
                    "name" => $current_user->name,
                    "email" => $current_user->email,
                    "document" => $data['doc'],
                    "documentType" => strlen($data['doc']) <= 11 ? 1 : 2, // 1 - cpf/ 2 - cnpj
                    "phone" => [
                        "number" => substr($current_user->phone, 4),
                        "countryCode" => substr($current_user->phone, 0, 2),
                        "areaCode" => substr($current_user->phone, 2, 2),
                        "type" => 5 // descobrir espeficicações para poder criar regra
                    ],
                    "address" => [
                        "street" => $complete->street,
                        "number" => $complete->number,
                        "neighborhood" => $complete->locality,
                        "city" => $complete->city,
                        "state" => $complete->region_code,
                        "country" => $complete->country == 'BRA' ? 'BR' : $complete->country,
                        "zipCode" => $complete->postal_code,
                        "complement" => $complete->complement
                    ]
                ],
                "transactions" => [
                    [
                        "temporaryCardToken" => $data['temporaryCardToken'],
                        "paymentType" => 2, // Tipo de transação 2 para crédito
                        "amount" => $this->convertDecimalToCents($data['amount']),
                        "installmentNumber" => (int) $data['installmentNumber'],
                        "softDescriptor" => "CALLOREAQUETOALHAS"
                    ]
                ],
                "source" => 1
            ]
        ];

        // "installmentType" => 1, // 1 sem juros 2 com juros "none" para a vista
        if ($payload['charge']['transactions'][0]['installmentNumber'] > 1) {
            $payload['charge']['transactions'][0]['installmentType'] = 1;
        }

        return $payload;
    }

    private function makePayloadSafraPix($data, $current_user)
    {
        $payload = [
            "charge" => [
                "merchantChargeId" => $data['merchantChargeId'],
                "customer" => [
                    "name" => $current_user->name,
                    "email" => $current_user->email,
                    "document" => $data['doc'],
                    "documentType" => strlen($data['doc']) <= 11 ? 1 : 2, // 1 - cpf/ 2 - cnpj
                    "phone" => [
                        "number" => substr($current_user->phone, 4),
                        "countryCode" => substr($current_user->phone, 0, 2),
                        "areaCode" => substr($current_user->phone, 2, 2),
                        "type" => 5 // descobrir espeficicações para poder criar regra
                    ]
                ],
                "transactions" => [
                    [
                        "amount" => $this->convertDecimalToCents($data['amount']),
                    ]
                ],
                "source" => 1
            ]
        ];

        return $payload;
    }

    // Convert um valor decimal em centavos
    private function convertDecimalToCents($value)
    {
        return number_format(number_format($value, 2, '.', '') * 100, 0, '', '');
    }
}
