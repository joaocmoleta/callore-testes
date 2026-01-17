<?php

namespace App\Http\Controllers;

use App\Helpers\CartHelper;
use App\Helpers\CouponHelper;
use App\Helpers\PagSeguroHelper;
use App\Helpers\StatesHelper;
use App\Jobs\BoletoPagSegJob;
use App\Jobs\CreditPagarMeSent;
use App\Jobs\CreditPagSegJob;
use App\Models\Complete;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderEvent;
use App\Models\PagarmePedidos;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Rules\FullNameRule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class CheckoutController extends Controller
{
    public function generate_session()
    {
        $session = '';
        if (session()->has('pagseguro_session')) {
            $session = session()->get('pagseguro_session');
        } else {
            $session_cur = json_decode($this->cur_pagseguro());

            if ($session_cur->id) {
                $session = $session_cur->id;
                session()->put('pagseguro_session', $session);
                session()->save();
            } else {
                return response(json_encode('erro'), 500)
                    ->header('Content-Type', 'application/json');
            }
        }

        return response(json_encode($session), 200)
            ->header('Content-Type', 'application/json');
    }

    private function cur_pagseguro()
    {
        $url = env('PAGSEGURO_URL_WS') . "v2/sessions?email=" . env('PAGSEGURO_EMAIL') . "&token=" . env('PAGSEGURO_TOKEN');

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded; charset=UTF-8"));
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $xml = simplexml_load_string($response);

        return json_encode($xml);
    }

    public function pay_pix_pagarme($order_id)
    {
        session()->flash('pay', 'pix_pagarme');

        $order = Order::select('orders.status', 'orders.payment')
            ->where('id', $order_id)
            ->first();

        if ($order->status == 'paid') {
            return back()->withInput()->with('error', 'Este pedido já foi pago.');
        }

        if ($order->status == 'processing') {
            return back()->withInput()->with('error', 'Este pedido já está em processamento.');
        }

        $pagarme = PagarmePedidos::select('expires_at')
            ->where('order', $order_id)
            ->first();

        if ($pagarme && !Carbon::now()->lessThan(Carbon::parse($pagarme->expires_at))) { // Se existir um pagarmepedidos e a expiração do PIX ainda não ocorreu
            Payment::where('id', $order->payment)
                ->update([
                    'type' => 'PIX'
                ]);

            Log::info(static::class . ' linha ' . __LINE__ . " Pedido ($order_id): PIX selecionado como forma de pagamento.");

            return back()->withInput()->with('success', 'Já existe uma chave PIX gerada.');
        }

        // Caso não encontre pix para pagar em aberto para o pedido e ele ainda está em aberto
        // Rodar requisição para o pagarme pedindo chave PIX
        $response_obj = $this->requestPixPagarme($order_id);

        if (!$response_obj) {
            return back()->withInput()->with('error', 'Erro ao gerar QR Code, contate o administrador.');
        }

        $fields = [
            'order' => $order_id,
            'id_order' => $response_obj->id,
            'code' => $response_obj->code,
            'closed' => $response_obj->closed,
            'pg_created_at' => $response_obj->created_at,
            'pg_updated_at' => $response_obj->updated_at,
            'charge_id' => $response_obj->charges[0]->id,
            'charge_code' => $response_obj->charges[0]->code,
            'gateway_id' => $response_obj->charges[0]->gateway_id,
            'paid_amount' => $response_obj->charges[0]->amount,
            'qr_code' => $response_obj->charges[0]->last_transaction->qr_code,
            'qr_code_url' => $response_obj->charges[0]->last_transaction->qr_code_url,
            'expires_at' => $response_obj->charges[0]->last_transaction->expires_at,
            'transaction_id' => $response_obj->charges[0]->last_transaction->id,
        ];

        if ($pagarme) {
            PagarmePedidos::where('order', $order_id)->update($fields);
        } else {
            PagarmePedidos::create($fields);
        }

        $payment_db = Payment::select('id')->where('id', $order->payment)->first();

        $fields_payment = [
            'amount' => $response_obj->amount,
            'type' => 'PIX'
        ];

        if ($payment_db) {
            Payment::where('id', $payment_db->id)->update($fields_payment);
            $payment = $payment_db->id;
        } else {
            $payment = Payment::create($fields_payment);
            $payment = $payment->id;
        }

        Order::where('id', $order_id)
            ->update([
                'status' => $response_obj->charges[0]->last_transaction->status,
                'payment' => $payment
            ]);

        OrderEvent::create(['order' => $order_id, 'status' => $response_obj->charges[0]->last_transaction->status]);

        Log::info(static::class . ' linha ' . __LINE__ . " Pedido ($order_id): foi gerado uma chave PIX.");

        return back()->withInput()->with('success', 'Chave PIX gerada.');
    }

    private function requestPixPagarme($order_id)
    {
        if (env('PAGARME_URL') == '' || env('PAGARME_SK') == '') {
            Log::error(static::class . ' linha ' . __LINE__ . ' Erro nas envioments utilizadas no pagamento.');
            Log::info(static::class . ' linha ' . __LINE__ . ' PAGARME_DEBUG: ' . env('PAGARME_DEBUG'));
            Log::info(static::class . ' linha ' . __LINE__ . ' PAGARME_URL: ' . env('PAGARME_URL'));
            Log::info(static::class . ' linha ' . __LINE__ . ' PAGARME_SK: ' . env('PAGARME_SK'));
            Log::info(static::class . ' linha ' . __LINE__ . ' PAGARME_SK_PWD: ' . env('PAGARME_SK_PWD'));
            exit();
        }

        Log::info(static::class . ' linha ' . __LINE__ . " Pedido ($order_id): iniciado preparo dos dados para pagamento PIX PagarMe.");

        $order = Order::select([
            'orders.amount',
            'orders.products',
            'users.name',
            'users.email',
            'users.doc',
            'users.phone'
        ])->where('orders.id', $order_id)
            ->leftJoin('users', 'orders.user', 'users.id')
            ->first();

        $doc = preg_replace('/[^\d]/', '', $order->doc);
        if (strlen($doc) > 11) {
            $doc_type['type'] = 'CNPJ';
            $doc_type['type_ext'] = 'company';
        } else {
            $doc_type['type'] = 'CPF';
            $doc_type['type_ext'] = 'individual';
        }

        $payload = [
            "customer" => [
                "name" => $order->name,
                "type" => $doc_type['type_ext'],
                "email" => $order->email,
                "document" => preg_replace('/[^\d]/', '', $order->doc),
                "document_type" => $doc_type['type'],
                "phones" => [
                    "mobile_phone" => [
                        "country_code" => substr($order->phone, 0, 2), //"55" 
                        "area_code" => substr($order->phone, 2, 2), // "41"
                        "number" => substr($order->phone, 4) // "998410336"
                    ]
                ]
            ],
            "items" => [],
            "payments" => [
                [
                    "Pix" => [
                        "expires_in" => 1800
                    ],
                    "payment_method" => "pix"
                ]
            ],
            "closed" => true,
            "ip" => "52.168.67.32",
            "session_id" => "322b821a",
            "antifraud_enabled" => true
        ];

        // dd($payload);

        $items = json_decode($order->products);

        foreach ($items as $item) {
            $amount = $item->value_uni;
            $amount = PagSeguroHelper::instance()->format_amount($amount);

            $payload['items'][] = [
                "amount" => (int) $amount, // valor unitário
                "description" => $item->name,
                "quantity" => (int) $item->qtd,
                "code" => $item->id
            ];
        }

        $payload = json_encode($payload);

        // dd($payload);

        // json_encode($fields);
        // Log::info($fields);
        // exit();

        // $payload = '{"customer": {"address": {"country": "BR","state": "BR-PR","city": "Curitiba","zip_code": "83030000","line_1": "Rua das Flores, 33, Centro","line_2": "Ap 2020"},"phones": {"mobile_phone": {"country_code": "55","area_code": "41","number": "999999999"}},"name": "Tony Stark","type": "individual","email": "tonystark@stark.com.br","code": "52","document": "29241451041","document_type": "CPF","gender": "male","birthdate": "01/01/1990"},"items": [{"amount": 2990,"description": "Aquecedor de Toalhas Versátil Branco","quantity": 1,"code": "93"}],"payments": [{"Pix": {"expires_in": 1800},"payment_method": "pix"}],"closed": true,"ip": "52.168.67.32","session_id": "322b821a","antifraud_enabled": true}';

        if (env('PAGSEGURO_DEBUG')) {
            ob_start();
            $out = fopen('php://output', 'w');
        }

        $curl = curl_init(env('PAGARME_URL'));

        if (env('PAGSEGURO_DEBUG')) {
            curl_setopt($curl, CURLOPT_VERBOSE, true);
            curl_setopt($curl, CURLOPT_STDERR, $out);
        }

        Log::info(static::class . ' linha ' . __LINE__ . " Pedido ($order_id) - payload " . $payload);

        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . base64_encode(env('PAGARME_SK') . ':' . env('PAGARME_SK_PWD')),
            'Content-Type: application/json',
        ]);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);

        Log::info(static::class . ' linha ' . __LINE__ . " Pedido ($order_id): Iniciando transmissão dos dados...");

        $response = curl_exec($curl);

        if (env('PAGSEGURO_DEBUG')) {
            fclose($out);
            $debug = ob_get_clean();

            Log::info(static::class . ' linha ' . __LINE__ . " Pedido ($order_id) - debug: " . $debug);
        }

        $err = curl_error($curl);

        curl_close($curl);

        Log::info(static::class . ' linha ' . __LINE__ . " Pedido ($order_id) - curl_getinfo: " . curl_getinfo($curl, CURLINFO_HTTP_CODE));

        $errors = false;
        if ($err) {
            Log::error(static::class . ' linha ' . __LINE__ . " Pedido ($order_id) - curl_error: " . curl_error($curl));
            $errors = true;
        } else {
            Log::info(static::class . ' linha ' . __LINE__ . " Pedido ($order_id) - retorno pagarme: " . $response);

            $response = json_decode($response);

            if (isset($response->message) && isset($response->errors)) {
                Log::error(static::class . ' linha ' . __LINE__ . " Pedido ($order_id): " . json_encode($response->errors));
                $errors = true;
            }
            if (isset($response->charges[0]->last_transaction->gateway_response->errors)) {
                Log::error(static::class . ' linha ' . __LINE__ . " Pedido ($order_id):" . json_encode($response->charges[0]->last_transaction->gateway_response));
                $errors = true;
            }
        }

        Log::info(static::class . ' linha ' . __LINE__ . " Pedido ($order_id): finalizado envio de pagamento pix PagarMe com ou sem erros.");

        if ($errors) {
            return false;
        }

        return $response;
    }

    public function pay_credit_card_pagarme(Request $request)
    {
        session()->flash('pay', 'credit_card_pagarme');

        $request->validate([
            'reference_id' => 'required',
            'pagarmetoken-0' => 'required',
            'name' => ['required', new FullNameRule()],
            'installments' => 'required|between:1,12',
        ]);

        $order = Order::select(
            'id',
            'coupon',
            'products',
            'amount',
            'payment',
            'status',
            'user'
        )
            ->where('id', $request->reference_id)
            ->first();

        $payment = Payment::select('type')
            ->where('id', $order->payment)
            ->first();

        $amount = $order->amount;

        if ($order->coupon) {
            $calc_coupon = CouponHelper::instance()->get_amount($order->coupon, json_decode($order->products), false, $payment->type)['amount'];

            if ($calc_coupon) {
                $amount = $calc_coupon;
                $coupon_db = Coupon::select('id', 'valid')->where('code', $order->coupon)->first();
                if ($coupon_db) {
                    $qtd_valid = $coupon_db->valid;
                    if ($qtd_valid != -1) {
                        if ($coupon_db->update(['valid' => $qtd_valid - 1])) {
                            Log::info("Removido uso do cupom $coupon_db->id." . $qtd_valid - 1 . "restanes");
                        } else {
                            Log::error("Falha ao remover uso do cupom $coupon_db->id.");
                        }
                    }
                }
            }
        }

        if ($request->installments > 6) {
            $amount = $amount * (json_decode(env('PAGARME_PARCELAMENTO'), 1)[$request->installments] / 100) + $amount;
        }

        $payment = Payment::create([
            'amount' => $amount,
            'type' => 'CREDIT_CARD',
            'installments' => $request->installments,
            'cd_holder_name' => $request->name,
        ]);

        if ($payment) {
            Log::info(static::class . ' linha ' . __LINE__ . " Pedido ($order->id): criado novo pagamento.");
        } else {
            Log::error(static::class . ' linha ' . __LINE__ . " Pedido ($order->id): falha ao criar pagamento.");
        }

        $up_order = Order::where('id', $request->reference_id)->update([
            'status' => 'processing',
            'payment' => $payment->id,
            'amount' => $amount
        ]);

        if ($up_order) {
            Log::info(static::class . ' linha ' . __LINE__ . " Pedido ($order->id): atualizado informações do pedido.");
        } else {
            Log::error(static::class . ' linha ' . __LINE__ . " Pedido ($order->id): falha ao atualizar as informações.");
        }

        OrderEvent::create(['order' => $request->reference_id, 'status' => 'processing']);

        // limpar carrinho
        CartHelper::instance()->remove_cart();

        $arr = [
            'order' => $request->post('reference_id'),
            'payment' => [
                'pagarmetoken-0' => $request->post('pagarmetoken-0'),
                'name' => $request->post('name'),
                'ip' => $_SERVER['REMOTE_ADDR'],
                'installments' => $request->post('installments'),
            ]
        ];

        // Validar se tem CNPJ/CPF
        Log::info(static::class . ' linha ' . __LINE__ . ' Validando Documento do usuário.');
        $user = User::select('id', 'doc')
            ->where('id', $order->user)
            ->first();

        Log::info($user->doc);

        if (!isset($user->doc)) {
            Log::error(static::class . ' linha ' . __LINE__ . ' Usuário não possuí documento preenchido.');
            return redirect('profile.edit', $user->id)->withInput()->with('error', 'O documento não é válido, atualize-o.');
        }

        if (CreditPagarMeSent::dispatch($arr)) {
            Log::info(static::class . ' linha ' . __LINE__ . " Pedido ($order->id): agendado preparo e envio de solicitação de pagamento.");
        }

        return back()->withInput()->with('success', 'Pagamento em processamento.');
    }

    public function pay_credit_card(Request $request)
    {
        session()->flash('pay', 'credit_card');

        $request->merge([
            'phone' => preg_replace('/[^0-9]/', '', $request->phone),
            'card_number' => preg_replace('/[^0-9]/', '', $request->card_number),
        ]);

        $validated = $request->validate(
            [
                'reference_id' => 'required',
                'ddi' => 'required|digits:2',
                'ddd' => 'required|digits:2',
                'phone' => 'required|integer:10000000,999999999',
                'installments' => 'required|between:1,12',
                'card_holder_name' => ['required', new FullNameRule()],
                'card_number' => 'required|numeric|digits:16',
                'card_security_code' => 'required|numeric|between:001,9999',
                'encrypted' => 'required',
            ],
            [
                'installments' => 'Número de parcelas entre 1 a 12.',
                'card_holder_name' => 'Nome completo, igual ao impresso no cartão.',
                'card_number' => 'N⁰ cartão deve ter 16 números.',
                'card_security_code' => 'Código de segurança inválido.',
                'encrypted' => 'Falha ao criptografar o cartão, tente novamente. Se persistir, entre em contato conosco.',
            ]
        );

        $order = Order::select('id', 'products', 'coupon', 'payment', 'user')->where('id', $validated['reference_id'])->first();

        $products_order = json_decode($order->products);
        $amount = $this->calc_amount($products_order);

        $parcelamento_min = $amount / $validated['installments'];

        if ($parcelamento_min < env('PARCELA_MIN')) {
            return back()->withInput()->with('error', 'Valor mínimo da parcela precisa ser de R$ ' . env('PARCELA_MIN') . '.');
        }

        if ($order->coupon) {
            $calc_coupon = CouponHelper::instance()->get_amount($order->coupon, $products_order)['amount'];
            if ($calc_coupon) {
                $validated['amount'] = $calc_coupon;
                $coupon_db = Coupon::select('id', 'valid')->where('code', $order->coupon)->first();
                if ($coupon_db) {
                    $qtd_valid = $coupon_db->valid;
                    if ($qtd_valid != -1) {
                        $coupon_db->update(['valid' => $qtd_valid - 1]);
                    }
                }
            } else {
                $validated['amount'] = $amount;
            }
        } else {
            $validated['amount'] = $amount;
        }

        if ($order->payment) {
            $payment = Payment::where('id', $order->payment)->first();
            $payment->type = 'CREDIT_CARD';
            $payment->amount = $validated['amount'];
            $payment->installments = $request->installments;
            $payment->cd_holder_name = $request->card_holder_name;
            $payment->cd_first_digits = substr($request->card_number, 0, 6);
            $payment->cd_last_digits = substr($request->card_number, -4, 4);
            $payment->save();
        } else {
            $payment = Payment::create($validated);
        }

        $order->status = 'processing';
        $order->amount = $validated['amount'];
        $order->payment = $payment->id;
        $order->save();

        $validated['type'] = 'CREDIT_CARD';
        $validated['description'] = 'Pagamento do pedido ' . $validated['reference_id'];
        $validated['user'] = auth()->user();
        $validated['complete'] = Complete::select(
            'tax_id',
            'street',
            'number',
            'complement',
            'locality',
            'city',
            'region_code',
            'country',
            'postal_code'
        )->where('user', $validated['user']->id)
            ->first();

        CreditPagSegJob::dispatch($validated);

        OrderEvent::create(['order' => $order->id, 'status' => 'processing']);

        foreach ($products_order as $item) {
            $product_db = Product::select('id', 'qtd')->where('id', $item->id)->first();
            $inventory = $product_db->qtd;
            $product_db->update(['qtd' => $inventory - $item->qtd]);
        }

        // limpar carrinho
        CartHelper::instance()->remove_cart();

        return back()->withInput()->with('success', 'Seu pagamento está sendo processado.');
    }

    public function pay_pix(Request $request)
    {
        session()->flash('pay', 'pix');

        $request->merge([
            'phone' => preg_replace('/[^0-9]/', '', $request->phone),
            'tax_id' => preg_replace('/[^0-9]/', '', $request->tax_id),
            'postal_code' => preg_replace('/[^0-9]/', '', $request->postal_code),
        ]);

        $validated = $request->validate(
            [
                'reference_id' => 'required',
                'card_holder_name' => ['required', 'min:3', new FullNameRule()],
                'email' => 'required|email',
                'tax_id' => 'required|cpf',
                'ddi' => 'required|digits:2',
                'ddd' => 'required|digits:2',
                'phone' => 'required|integer:10000000,999999999',
                'postal_code' => 'required|digits:8',
                'street' => 'required',
                'number' => 'required',
                'complement' => '',
                'locality' => 'required',
                'city' => 'required',
                'region_code' => 'required',
                'country' => 'required',
            ],
            [
                'card_holder_name' => 'Nome incorreto, insira o nome completo.',
                'email' => 'E-mail incorreto.',
                'tax_id' => 'CPF incorreto.',
                'ddi' => 'DDI incorreto.',
                'ddd' => 'DDD incorreto.',
                'phone' => 'Telefone incorreto.',
                'postal_code' => 'CEP incorreto.',
                'street' => 'Rua incorreta.',
                'number' => 'Número incorreto.',
                'complement' => 'Complemento incorreto.',
                'locality' => 'Bairro incorreto.',
                'city' => 'Cidade incorreta.',
                'region_code' => 'Estado incorreto.',
                'country' => 'País incorreto.',
            ]
        );

        $order = Order::select('id', 'products', 'coupon', 'user', 'payment')->where('id', $validated['reference_id'])->first();

        User::select('id')->where('id', $order->user)->first()->update([
            'phone' => $validated['ddi'] . $validated['ddd'] . $validated['phone']
        ]);

        $products_order = json_decode($order->products);
        $amount = $this->calc_amount($products_order);

        if ($order->coupon) {
            $validated['amount'] = CouponHelper::instance()->get_amount($order->coupon, $products_order)['amount'];
            $coupon_db = Coupon::select('id', 'valid')->where('code', $order->coupon)->first();
            $qtd_valid = $coupon_db->valid;
            if ($qtd_valid != -1) {
                $coupon_db->update(['valid' => $qtd_valid - 1]);
            }
        } else {
            $validated['amount'] = $amount;
        }

        $validated['description'] = 'Pagamento do pedido ' . $validated['reference_id'];
        $validated['region'] = StatesHelper::instance()->get_state($validated['region_code']);

        if ($order->payment) {
            $payment = Payment::where('id', $order->payment)->first();
            $payment->type = 'PIX';
            $payment->tax_id = $validated['tax_id'];
            $payment->postal_code = $validated['postal_code'];
            $payment->street = $validated['street'];
            $payment->number = $validated['number'];
            $payment->complement = $validated['complement'];
            $payment->locality = $validated['locality'];
            $payment->city = $validated['city'];
            $payment->region = $validated['region'];
            $payment->region_code = $validated['region_code'];
            $payment->country = $validated['country'];
            $payment->save();
        } else {
            $validated['type'] = 'PIX';
            $validated['order'] = $validated['reference_id'];
            $payment = Payment::create($validated);
        }

        $order->status = 'processing';
        $order->amount = $validated['amount'];
        $order->payment = $payment->id;
        $order->save();

        if ($complete = Complete::where('user', $order->user)->first()) {
            $complete->tax_id = $validated['tax_id'];
            $complete->postal_code = $validated['postal_code'];
            $complete->street = $validated['street'];
            $complete->number = $validated['number'];
            $complete->complement = $validated['complement'];
            $complete->locality = $validated['locality'];
            $complete->city = $validated['city'];
            $complete->region = $validated['region'];
            $complete->country = $validated['country'];
            $complete->save();
        } else {
            $validated['user'] = $order->user;
            $complete = Complete::create($validated);
        }

        // PixPagSeg::dispatch($validated);
        $this->requestPix($validated);

        OrderEvent::create(['order' => $order->id, 'status' => 'waiting_pay_pix']);

        // limpar carrinho
        CartHelper::instance()->remove_cart();

        // return 'Finalizado';
        return back()->withInput()->with('success', 'QR Code gerado com sucesso.');
    }

    private function requestPix($data)
    {
        settype($amount, "integer");
        $amount = PagSeguroHelper::instance()->format_amount($data['amount']);

        $fields = json_encode([
            'reference_id' => $data['reference_id'],
            'customer' => [
                'name' => $data['card_holder_name'],
                'email' => $data['email'],
                'tax_id' => $data['tax_id'],
                'phones' => [
                    [
                        'country' => $data['ddi'],
                        'area' => $data['ddd'],
                        'number' => $data['phone'],
                        'type' => 'MOBILE'
                    ]
                ]
            ],
            'items' => [
                [
                    'name' => 'outros',
                    'quantity' => 1,
                    'unit_amount' => $amount
                ]
            ],
            'qr_codes' => [
                [
                    'amount' => [
                        'value' => $amount
                    ]
                ]
            ],
            'shipping' => [
                'address' => [
                    'street' => $data['street'],
                    'number' => $data['number'],
                    'complement' => $data['complement'],
                    'locality' => $data['locality'],
                    'city' => $data['city'],
                    'region_code' => $data['region_code'],
                    'country' => $data['country'],
                    'postal_code' => $data['postal_code']
                ]
            ],
            'notification_urls' => [
                env('NOTIFICATION_URL')
            ]
        ]);

        if (env('PAGSEGURO_DEBUG')) {
            ob_start();
            $out = fopen('php://output', 'w');
        }

        $curl = curl_init(env('PAGSEGURO_URL') . 'orders');

        if (env('PAGSEGURO_DEBUG')) {
            curl_setopt($curl, CURLOPT_VERBOSE, true);
            curl_setopt($curl, CURLOPT_STDERR, $out);
        }

        Log::info("fields (CheckoutController requestPIX): " . $fields);

        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: ' . env('PAGSEGURO_TOKEN'),
            'Content-Type: application/json',
        ]);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);

        $response = curl_exec($curl);

        if (env('PAGSEGURO_DEBUG')) {
            fclose($out);
            $debug = ob_get_clean();

            Log::info(static::class . ' linha ' . __LINE__ . ' Debug (CheckoutController requestPIX): ' . $debug);
        }

        $err = curl_error($curl);

        curl_close($curl);

        Log::info(static::class . ' linha ' . __LINE__ . ' curl_getinfo (CheckoutController requestPIX): ' . curl_getinfo($curl, CURLINFO_HTTP_CODE));

        if ($err) {
            Log::error(static::class . ' linha ' . __LINE__ . ' curl_error (CheckoutController requestPIX): ' . curl_error($curl));
        } else {
            Log::info(static::class . ' linha ' . __LINE__ . ' Retorno pagseguro (CheckoutController requestPIX): ' . $response);

            $res = json_decode($response);

            if (isset($res->reference_id)) {
                $this->processPix($res);
            } else {
                Order::select('id')->where('id', $data['reference_id'])->first()->update([
                    'status' => 'declined'
                ]);

                OrderEvent::create([
                    'order' => $data['reference_id'],
                    'status' => 'declined'
                ]);
            }
        }
    }

    private function processPix($res)
    {
        // Gravar ordem status
        $order = Order::select('id', 'status', 'payment')->where('id', $res->reference_id)->first();

        $new_status = 'declined';
        if ($res->qr_codes) {
            $new_status = 'waiting_pay_pix';
        }
        $order->status = $new_status;
        $order->save();

        // Gravar payment pay_id
        $payment_data = [
            'pay_id' => $res->id,
            'amount' => PagSeguroHelper::instance()->amount_to_double($res->qr_codes[0]->amount->value),
            'cd_holder_name' => $res->customer->name,
            'boleto_id' => $res->qr_codes[0]->id,
            'barcode' => $res->qr_codes[0]->text,
            'due_date' => $res->qr_codes[0]->expiration_date,
            'links' => json_encode($res->qr_codes[0]->links),
        ];

        $payment = Payment::where('id', $order->payment)->update($payment_data);
        if (!$payment) {
            Payment::create($payment_data);
        }

        OrderEvent::create([
            'order' => $res->reference_id,
            'status' => $new_status,
        ]);
    }

    private function calc_amount($products)
    {
        $amount = 0;
        foreach ($products as $pro) {
            $amount += Product::select('value')->where('id', $pro->id)->first()->value * $pro->qtd;
        }
        return $amount;
    }

    public function pay_boleto(Request $request)
    {
        session()->flash('pay', 'boleto');

        $request->merge([
            'tax_id' => preg_replace('/[^0-9]/', '', $request->tax_id),
            'postal_code' => preg_replace('/[^0-9]/', '', $request->postal_code),
        ]);

        $validated = $request->validate(
            [
                'reference_id' => 'required',
                'card_holder_name' => ['required', 'min:3', new FullNameRule()],
                'tax_id' => 'required|cpf',
                'email' => 'required|email',
                'postal_code' => 'required|digits:8|integer',
                'street' => 'required',
                'number' => 'required',
                'locality' => 'required',
                'city' => 'required',
                'region_code' => 'required',
                'country' => 'required',
            ],
            [
                'card_holder_name' => 'Nome incorreto, insira o nome completo.',
                'tax_id' => 'CPF incorreto.',
                'email' => 'E-mail incorreto.',
                'postal_code' => 'CEP incorreto.',
                'street' => 'Rua incorreta.',
                'number' => 'Número incorreto.',
                'locality' => 'Bairro incorreto.',
                'city' => 'Cidade incorreta.',
                'region_code' => 'Estado incorreto.',
                'country' => 'País incorreto.',
            ]
        );

        $order = Order::select('id', 'products', 'coupon', 'user', 'payment')->where('id', $validated['reference_id'])->first();

        $products_order = json_decode($order->products);
        $amount = $this->calc_amount($products_order);

        if ($order->coupon) {
            $validated['amount'] = CouponHelper::instance()->get_amount($order->coupon, $products_order)['amount'];
            $coupon_db = Coupon::select('id', 'valid')->where('code', $order->coupon)->first();
            $qtd_valid = $coupon_db->valid;
            if ($qtd_valid != -1) {
                $coupon_db->update(['valid' => $qtd_valid - 1]);
            }
        } else {
            $validated['amount'] = $amount;
        }

        $validated['description'] = 'Pagamento do pedido ' . $validated['reference_id'];
        $validated['region'] = StatesHelper::instance()->get_state($validated['region_code']);

        if ($order->payment) {
            $payment = Payment::where('id', $order->payment)->first();
            $payment->type = 'Boleto';
            $payment->tax_id = $validated['tax_id'];
            $payment->postal_code = $validated['postal_code'];
            $payment->street = $validated['street'];
            $payment->number = $validated['number'];
            $payment->locality = $validated['locality'];
            $payment->city = $validated['city'];
            $payment->region = $validated['region'];
            $payment->region_code = $validated['region_code'];
            $payment->country = $validated['country'];
            $payment->save();
        } else {
            $validated['type'] = 'Boleto';
            $validated['order'] = $validated['reference_id'];
            $payment = Payment::create($validated);
        }

        $order->status = 'processing';
        $order->amount = $validated['amount'];
        $order->payment = $payment->id;
        $order->save();

        if ($complete = Complete::where('user', $order->user)->first()) {
            $complete->tax_id = $validated['tax_id'];
            $complete->postal_code = $validated['postal_code'];
            $complete->street = $validated['street'];
            $complete->number = $validated['number'];
            $complete->locality = $validated['locality'];
            $complete->city = $validated['city'];
            $complete->region = $validated['region'];
            $complete->country = $validated['country'];
            $complete->save();
        } else {
            $validated['user'] = $order->user;
            $complete = Complete::create($validated);
        }

        BoletoPagSegJob::dispatch($validated);

        OrderEvent::create(['order' => $order->id, 'status' => 'processing']);

        foreach ($products_order as $item) {
            $product_db = Product::select('id', 'qtd')->where('id', $item->id)->first();
            $inventory = $product_db->qtd;
            $product_db->update(['qtd' => $inventory - $item->qtd]);
        }

        // limpar carrinho
        CartHelper::instance()->remove_cart();

        return back()->withInput()->with('success', 'Seu pagamento está sendo processado.');
    }
}
