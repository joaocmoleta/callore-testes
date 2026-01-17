<?php

namespace App\Http\Controllers;

use App\Helpers\CartHelper;
use App\Helpers\CouponHelper;
use App\Helpers\StatesHelper;
use App\Jobs\CancelChargePagarme;
use App\Jobs\CancelOrder;
use App\Jobs\EstornoJob;
use App\Jobs\MailCancelChargePagarme;
use App\Jobs\NewOrderJob;
use App\Jobs\OrderDeliveredJob;
use App\Jobs\OrderDeliveryTime;
use App\Jobs\OrderLastStepJob;
use App\Jobs\OrderShippedJob;
use App\Jobs\OrderStatusJob;
use App\Jobs\PaidOrderJob;
use App\Jobs\PrepareOrderJob;
use App\Models\Complete;
use App\Models\Delivery;
use App\Models\Encomenda;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderEvent;
use App\Models\PagarmePedidos;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::select('orders.id', 'user', 'amount', 'status', 'orders.created_at', 'users.name')
            ->join('users', 'orders.user', '=', 'users.id')
            ->orderBy('id', 'DESC')
            ->paginate(8);
        return view('dashboard.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.orders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required',
            'user' => 'required',
            'amount' => 'required|numeric',
            'status' => 'required',
            'coupon' => '',
        ]);

        // $validated['coupon'] = $request->coupon;

        if (!Order::create($validated)) {
            return redirect()->back()->with('error', 'Falha ao adicionar.');
        }

        return redirect(route('orders.index'))->with('success', 'Adicionado com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return view('dashboard.orders.edit', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        $order = Order::select(
            'orders.id',
            'orders.products',
            'orders.amount',
            'orders.status',
            'orders.coupon',
            'orders.payment',
            'users.id as user_id',
            'users.name',
            'users.email',
            'users.phone',
            'completes.tax_id',
            'completes.postal_code',
            'completes.street',
            'completes.number',
            'completes.complement',
            'completes.locality',
            'completes.city',
            'completes.region_code',
            'completes.country',
            'py.id as py_id',
            'py.pay_id',
            'py.type',
            'py.amount as py_amount',
            'py.installments',
            'py.cd_brand',
            'py.cd_first_digits',
            'py.cd_last_digits',
            'py.cd_exp_month',
            'py.cd_exp_year',
            'py.cd_holder_name',
            'py.tax_id as payemnt_tax_id',
            'py.postal_code as payemnt_postal_code',
            'py.street as payemnt_street',
            'py.number as payemnt_number',
            'py.locality as payemnt_locality',
            'py.city as payemnt_city',
            'py.region_code as payemnt_region_code',
            'py.country as payemnt_country',
            'py.boleto_id as payemnt_boleto_id',
            'py.barcode as payemnt_barcode',
            'py.due_date as payemnt_due_date',
            'deliveries.id as delivery_id',
            'deliveries.code as delivery_code',
            'deliveries.method as delivery_method',
            'deliveries.status as delivery_status',
            'deliveries.estimative as delivery_estimative',
            'coupons.discount_type as coupon_discount_type',
            'coupons.product as coupon_discount_product',
            'coupons.discount as coupon_discount',
            'invoices.numero',
            'invoices.serie',
            'invoices.data_emissao',
            'invoices.total',
            'invoices.produto',
            'invoices.chave',
            'invoices.file',
            'invoices.created_at as adicionada',
            'invoices.volumes',
            'encomendas.pedido',
            'encomendas.data',
            'encomendas.hora',
        )->leftJoin('completes', 'completes.user', '=', 'orders.user')
            ->leftJoin('users', 'orders.user', '=', 'users.id')
            ->leftJoin('payments as py', 'orders.payment', '=', 'py.id')
            ->leftJoin('deliveries', 'deliveries.order', '=', 'orders.id')
            ->leftJoin('coupons', 'coupons.code', '=', 'orders.coupon')
            ->leftJoin('invoices', 'invoices.order', '=', 'orders.id')
            ->leftJoin('encomendas', 'encomendas.order', '=', 'orders.id')
            ->where('orders.id', $order->id)
            ->first();

        $order_events = OrderEvent::select(
            'order_events.id',
            'order_events.status',
            'order_events.user',
            'order_events.created_at',
            'users.name',
        )
            ->leftJoin('users', 'users.id', 'order_events.user')
            ->where('order_events.order', $order->id)
            ->orderBy('order_events.created_at', 'ASC')
            ->get();

        $products = json_decode($order->products);
        $total_products = 0;
        foreach ($products as $product) {
            $total_products += $product->subtotal;
        }

        $discount = CouponHelper::instance()->get_amount($order->coupon, $products, true);

        $status = [
            'new' => 'Novo',
            'waiting_pay' => 'Aguardando pagamento',
            'processing' => 'Processando',
            'waiting_pay_boleto' => 'Aguardando pagamento do boleto',
            'waiting_pay_pix' => 'Aguardando pagamento por PIX',
            'paid' => 'Pago',
            'declined' => 'Recusado',
            'canceled' => 'Cancelado',
            'preparing_your_order' => 'Preparando seu pedido',
            'nf_add' => 'Nota fiscal adicionada',
            'request_total_express' => 'Enviado a transportadora',
            // 'shipped' => 'Despachado',
            // 'last_stage_delivery' => 'No último estágio do percurso',
            // 'delivered' => 'Entregue',
        ];

        $encomenda = Encomenda::select('volumes')
            ->where('order', $order->id)
            ->first();


        return view('dashboard.orders.edit', compact('encomenda', 'order', 'products', 'discount', 'status', 'order_events', 'total_products'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'order' => 'required',
            'status' => 'required'
        ]);

        $update = Order::select('id')->where('id', $request->order)->first()->update(['status' => $request->status]);

        if ($request->notify) {
            OrderStatusJob::dispatch(['order' => $request->order, 'status' => $request->status, 'notify' => $request->notify]);
        }

        $current_user = $request->user();

        OrderEvent::create([
            'order' => $request->order,
            'status' => $request->status,
            'user' => $current_user->id
        ]);

        if (!$update) {
            Log::error(static::class . ' linha ' . __LINE__ . " Falha em atualizar pedido $request->order para $request->status por usuário id $current_user->id.");

            return redirect()->back()->with('error', 'Falha ao atualizar.');
        }

        Log::info(static::class . ' linha ' . __LINE__ . " Atualizado status do pedido $request->order para $request->status por usuário id $current_user->id.");

        return redirect()->back()->with('success', 'Atualizado com sucesso.');
    }

    public function storeDelivery(Request $request)
    {
        $validated = $request->validate(
            [
                'order' => 'required',
                'code' => '',
                'method' => '',
                'status' => '',
                'estimative' => 'date',
            ],
            [
                'code' => 'Código incorreto.',
                'method' => 'Método incorreto.',
                'status' => 'Status incorreto.',
                'estimative' => 'Data incorreta.',
            ]
        );

        $current_user = $request->user();

        if (!Delivery::create($validated)) {
            Log::error(static::class . ' linha ' . __LINE__ . " Falha ao atualizar dados de entrega por usuário id $current_user->id.");

            return redirect()->back()->with('error', 'Falha ao atualizar dados de entrega.');
        }

        $this->statusDispatch($request->status, $validated['order'], $request->notify);

        Log::info(static::class . ' linha ' . __LINE__ . " Sucesso ao atualizar dados de entrega por usuário id $current_user->id.");

        return redirect()->back()->with('success', 'Atualizado com sucesso.');
    }

    private function statusDispatch($status, $order, $notify)
    {
        // Cria evento
        OrderEvent::create([
            'order' => $order,
            'status' => $status,
        ]);

        Order::select('id')->where('id', $order)->first()->update(['status' => $status]);

        if (!$notify) {
            return;
        }

        switch ($status) {
            case 'shipped':
                OrderShippedJob::dispatch($order);
                break;
            case 'last_stage_delivery':
                OrderLastStepJob::dispatch($order);
                break;
            case 'delivered':
                OrderDeliveredJob::dispatch($order);
                break;
            case 'delivery_updated':
                OrderDeliveryTime::dispatch($order);
                break;
        }
    }

    public function upDelivery(Request $request)
    {
        $validated = $request->validate(
            [
                'order' => 'required',
                'code' => '',
                'method' => '',
                'status' => '',
                'estimative' => 'date',
            ],
            [
                'code' => 'Código incorreto.',
                'method' => 'Método incorreto.',
                'status' => 'Status incorreto.',
                'estimative' => 'Data incorreta.',
            ]
        );

        $update = Delivery::select('id')
            ->where('order', $request->order)
            ->first()
            ->update($validated);

        if (!$update) {
            return redirect()->back()->with('error', 'Falha ao atualizar.');
        }

        $this->statusDispatch($request->status, $validated['order'], $request->notify);

        return redirect()->back()->with('success', 'Atualizado com sucesso.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'products' => 'required',
            'user' => 'required',
            'amount' => 'required|numeric',
            'status' => 'required',
            'coupon' => '',
        ]);

        $order->products = $request->products;
        $order->user = $request->user;
        $order->amount = $request->amount;
        $order->status = $request->status;
        $order->coupon = $request->coupon;

        if (!$order->save()) {
            return redirect()->back()->with('error', 'Falha ao atualizar.');
        }

        return redirect()->back()->with('success', 'Atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        if (!$order->delete()) {
            return back()->with('error', 'Erro ao deletar.');
        }
        return back()->with('success', 'Deletado com sucesso.');
    }

    public function restore($order)
    {
        if (!Order::withTrashed()->find($order)->restore()) {
            return back()->with('error', 'Erro ao restaurar.');
        }
        return back()->with('success', 'Restaurado com sucesso.');
    }

    public function add(Request $req)
    {
        // Remover carrinho
        CartHelper::instance()->remove_cart();

        $user_logged = auth()->user();
        // Verifica se já existe uma ordem e se está está com status new
        // Desta forma evita-se duplicidade de pedidos
        $db_order = Order::where('user', $user_logged->id)
            ->where('status', 'new')
            ->first();

        // Se já possui, atualiza os dados
        if ($db_order) {
            $db_order->products = $req->products;
            $db_order->coupon = $req->coupon;
            $db_order->amount = $req->amount;

            CartHelper::instance()->add_coupon($req->coupon);

            if (!$db_order->save()) {
                return redirect()->back()->with('error', 'Falha ao atualizar checkout.');
            }

            Log::info(static::class . ' linha ' . __LINE__ . " Pedido $db_order->id atualizado do usuário $user_logged->name.");

            return redirect(route('orders.show_order', $db_order->id))->with('success', 'Pedido atualizado com sucesso.');
        } else {
            $validated = [
                'products' => $req->products,
                'user' => $user_logged->id,
                'status' => 'new',
                'amount' => $req->amount,
            ];

            $payment = Payment::create(['cd_holder_name' => $user_logged->name]);

            $validated['payment'] = $payment->id;

            if (!$save = Order::create($validated)) {
                return redirect()->back()->with('error', 'Falha ao iniciar checkout.');
            }

            NewOrderJob::dispatch($save);

            return redirect(route('orders.show_order', $save->id))->with('success', '🙂 Pedido criado com sucesso.');
        }
    }

    public function show_order($order)
    {
        $db_order = Order::select(
            'orders.id',
            'orders.created_at',
            'orders.products',
            'orders.user',
            'orders.status',
            'orders.coupon',
            'orders.amount',
            'payments.pay_id',
            'payments.amount AS payment_amount',
            'payments.type',
            'payments.installments',
            'payments.cd_last_digits',
            'payments.postal_code',
            'payments.street',
            'payments.number',
            'payments.complement',
            'payments.locality',
            'payments.city',
            'payments.region_code',
            'payments.country',
            'payments.barcode',
            'payments.due_date',
            'payments.links'
        )
            ->leftJoin('payments', 'orders.payment', '=', 'payments.id')
            ->where('orders.id', $order)
            ->first();

        if (!$db_order) {
            abort(404, 'Pedido não encontrado.');
        }

        $cur_usr_id = Auth::id();

        if ($cur_usr_id != $db_order->user && $cur_usr_id != 1) {
            abort(401);
        }

        if ($links = $db_order->links) {
            $links = json_decode($links);
        }

        $product_list = [];
        $amount = 0;
        $qtd = 0;
        if ($db_order) {
            foreach (json_decode($db_order->products) as $item) {
                $product = Product::select('id', 'title', 'slug', 'value')->where('slug', $item->slug)->first();
                $amount += $product->value * $item->qtd;
                $qtd += $item->qtd;
                $product_list[] = [
                    'id' => $product->id,
                    'name' => $product->title,
                    'slug' => $product->slug,
                    'qtd' => $item->qtd,
                    'value_uni' => $product->value,
                    'subtotal' => $product->value * $item->qtd
                ];
            }
        }

        $totals = (object)[
            'qtd' => $qtd,
            'amount' => $amount,
        ];

        $amount_complete = CouponHelper::instance()->get_amount($db_order->coupon, json_decode($db_order->products), false, $db_order->type);

        $cart = CartHelper::instance()->check_cart();

        $status_delivery = Delivery::select('order', 'code', 'method', 'status', 'estimative')->where('order', $db_order->id)->first();

        $events = OrderEvent::select('order', 'status', 'user')
            ->where('order', $db_order->id)
            ->get();

        $user = User::select('name', 'doc', 'email', 'phone')->where('id', $db_order->user)->first();

        $complete = Complete::select('tax_id', 'postal_code', 'street', 'number', 'complement', 'locality', 'city', 'region_code', 'country')->where('user', $db_order->user)->first();

        $payment = PagarmePedidos::select('qr_code', 'qr_code_url', 'expires_at', 'paid_amount')
            ->where('order', $order)
            ->first();

        if ($payment) {
            $pix_data = [
                'src' => $payment->qr_code_url, //"https://api.pagar.me/core/v5/transactions/tran_dMw3rzEtnt7GJk6n/qrcode?payment_method=pix",
                'expiration' => Carbon::parse($payment->expires_at)->format('d/m/y \à\s H:i:s'), //'4/06/24 às 11:00',
                'chave' => $payment->qr_code
            ];
        } else {
            $pix_data = [];
        }

        $encomenda = Encomenda::select('volumes')
            ->where('order', $db_order->id)
            ->first();

        $invoice = Invoice::select('numero')
            ->where('order', $db_order->id)
            ->first();

        return view('orders.show', compact('invoice', 'encomenda', 'db_order', 'product_list', 'totals', 'cart', 'links', 'amount', 'amount_complete', 'status_delivery', 'events', 'user', 'complete', 'payment', 'pix_data'));
    }

    private function GetPagSeguroSession()
    {
        if (env('PAGSEGURO_EMAIL') == '' || env('PAGSEGURO_TOKEN') == '' || env('PAGSEGURO_URL_WS') == '') {
            Log::error(static::class . ' linha ' . __LINE__ . ' Erro nas envioments utilizadas no get session pagseguro.');
            Log::info(static::class . ' linha ' . __LINE__ . ' PAGSEGURO_EMAIL: ' . env('PAGSEGURO_EMAIL'));
            Log::info(static::class . ' linha ' . __LINE__ . ' PAGSEGURO_TOKEN: ' . env('PAGSEGURO_TOKEN'));
            Log::info(static::class . ' linha ' . __LINE__ . ' PAGSEGURO_URL_WS: ' . env('PAGSEGURO_URL_WS'));
            return false;
        }

        $credenciais = [
            'email' => env('PAGSEGURO_EMAIL'),
            'token' => env('PAGSEGURO_TOKEN'),
        ];

        Log::info(static::class . ' linha ' . __LINE__ . ' env(\'PAGSEGURO_URL_WS\') . /v2/sessions\' :' . env('PAGSEGURO_URL_WS') . 'v2/sessions');
        $curl = curl_init(env('PAGSEGURO_URL_WS') . 'v2/sessions');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($credenciais));

        $resultado = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        Log::info(static::class . ' linha ' . __LINE__ . ' curl_getinfo (Sessão na ordem): ' . curl_getinfo($curl, CURLINFO_HTTP_CODE));

        if ($err) {
            Log::error(static::class . ' linha ' . __LINE__ . ' curl_error (Sessão na ordem): ' . curl_error($curl));
        } else {
            $result = simplexml_load_string($resultado);
            if (isset($result->id)) {
                return $result->id;
            } else {
                Log::error(static::class . ' linha ' . __LINE__ . ' Resultado do pagseguro (Sessão na ordem): ' . $result);
                return false;
            }
        }
    }

    public function list()
    {
        $cart = CartHelper::instance()->check_cart();
        $user_logged = auth()->user();
        $orders = Order::where('user', $user_logged->id)->get();

        return view('orders.index', compact('orders', 'cart'));
    }

    public function complete(Request $request)
    {
        $request->merge([
            'tax_id' => preg_replace('/[^0-9]/', '', $request->tax_id),
            'postal_code' => preg_replace('/[^0-9]/', '', $request->postal_code),
        ]);

        $validated = $request->validate(
            [
                'reference_id' => 'required',
                'tax_id' => 'required|cpf',
                'postal_code' => 'required|digits:8',
                'street' => 'required',
                'number' => 'required',
                'complement' => '',
                'locality' => 'required',
                'city' => 'required',
                'region_code' => 'required|size:2',
                'country' => 'required|size:3',
            ],
            [
                'tax_id' => 'CPF incorreto.',
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

        $order = Order::where('id', $validated['reference_id'])->first();
        $order->status = 'waiting_pay';
        $order->save();

        OrderEvent::create(['order' => $order->id, 'status' => $order->status]);

        unset($validated['reference_id']);

        $validated['region'] = StatesHelper::instance()->get_state($request->region_code);
        if (!$complete = Complete::where('user', auth()->id())->update($validated)) {
            $validated['user'] = auth()->id();
            Complete::create($validated);
        }

        Log::info(static::class . ' linha ' . __LINE__ . " Pedido ($order->id): adicionado endereço de entrega.");

        return back()->withInput()->with('success', '📦 Endereço de entrega adicionado.');
    }

    public function pagarmeCancelCharge($order_id)
    {
        $user_logged = auth()->user();
        $order = Order::select('user')->where('id', $order_id)->first();

        if (!$order) {
            abort(404, 'Pedido não encontrado.');
        }

        if ($user_logged->id != $order->user) {
            Log::error(static::class . ' Linha ' . __LINE__ . ' Usuário logado não é o usuário atribuído a ordem.');
            return back()->withInput()->with('error', 'Este pedido não pertence ao usuário logado.');
        }

        // Agendar job para enviar cancelamento
        // CancelChargePagarme::dispatch($order_id);
        // Log::info(static::class . ' Linha ' . __LINE__ . " Agendado cancelamento do pagamento para o pedido $order_id.");

        // Enviar email para adm cancelar pedido
        CancelOrder::dispatch($order_id);

        Order::select('id')->where('id', $order_id)->update([
            'status' => 'processing_cancel'
        ]);

        OrderEvent::create([
            'order' => $order_id,
            'status' => 'processing_cancel'
        ]);

        return back()->withInput()->with('success', 'Solicitado cancelamento do pagamento.');
    }

    public function cancel($id)
    {
        $order = Order::select('id', 'payment', 'status')->where('id', $id)->first();

        if (
            $order->status == 'new'
            || $order->status == 'waiting_pay'
            || $order->status == 'paid'
            || $order->status == 'processing'
            || $order->status == 'waiting_pay_boleto'
        ) {
        } else {
            return back()->withInput()->with('error', 'Não é possível cancelar o pedido.');
        }

        // Solicitar estorno || Cancelar boleto
        EstornoJob::dispatch($order);
        $new_status = 'processing_cancel';

        $order->status = $new_status;
        $order->save();

        OrderEvent::create(['order' => $order->id, 'status' => $new_status]);

        return back()->withInput()->with('success', 'Cancelamento solicitado.');
    }

    public function payed($id)
    {
        $order = Order::find($id);
        $order->status = 'paid';
        $order->save();

        PaidOrderJob::dispatch($order);

        return back()->withInput()->with('success', 'Marcado como pago.');
    }

    private function teste()
    {
        echo env('NOTIFICATION_URL');
        echo '<br>';
        echo env('PAGSEGURO_TOKEN');
        echo '<br>';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sandbox.api.pagseguro.com/charges',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            "reference_id": "ex-00001",
            "description": "Motivo do pagamento",
            "amount": {
                "value": 1000,
                "currency": "BRL"
            },
            "payment_method": {
                "type": "CREDIT_CARD",
                "installments": 1,
                "capture": false,
                "soft_descriptor": "My Store",    
                "card": {
                "number": "4111111111111111",
                "exp_month": "03",
                "exp_year": "2026",
                "security_code": "123",
                "holder": {
                    "name": "Jose da Silva"
                }
                }
            },
            "notification_urls": ["'
                . env('NOTIFICATION_URL') .
                '"],
            "metadata": {
                "Exemplo": "Aceita qualquer informação",
                "NotaFiscal": "123",
                "idComprador": "123456"
            }
            }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . env('PAGSEGURO_TOKEN'),
                'Content-Type: application/json',
                'x-api-version: 4.0',
                'x-idempotency-key: '
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;

        exit();
    }

    public function add_coupon(Request $req)
    {
        $validated = $req->validate(
            [
                'order' => 'required',
                'coupon' => 'required',
            ],
            [
                'coupon' => 'É necessário preencher cupom.'
            ]
        );

        if (!Order::select('id')->where('id', $req->order)->update([
            'coupon' => $req->coupon
        ])) {
            Log::error(static::class . ' linha ' . __LINE__ . " Pedido ($req->order): Falha ao adicionar o cupom ($req->coupon).");
            return redirect()->back()->with('error', 'Falha ao adicionar cupom.');
        }

        if ($req->coupon) {
            CartHelper::instance()->add_coupon($req->coupon);
        }

        Log::info(static::class . ' linha ' . __LINE__ . " Pedido ($req->order): Cupom ($req->coupon) adiconado ao pedido.");
        return back()->withInput()->with('success', 'Cupom adicionado.');
    }

    public function changePayment($order)
    {
        $order_db = Order::select('payment', 'status')
            ->where('id', $order)
            ->first();

        if ($order_db->status == 'waiting_payment') {
            // do
            Payment::where('id', $order_db->payment)
                ->update([
                    'type' => ''
                ]);
            Log::info(static::class . ' linha ' . __LINE__ . " Pedido ($order): tipo de pagamento removido.");
            return back()->withInput()->with('success', 'Uma nova forma de pagamento pode ser selecionada agora.');
        }

        return redirect()->back()->with('error', 'Este pedido não pode ter uma troca de pagamento.');
    }
}
