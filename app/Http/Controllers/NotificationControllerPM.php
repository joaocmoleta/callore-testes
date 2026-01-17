<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessNotificationPM;
use App\Models\Order;
use App\Models\OrderEvent;
use App\Models\PagarmePedidos;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationControllerPM extends Controller
{
    public function index(Request $request)
    {
        $response = $request->collect();

        Log::info(static::class . ' linha ' . __LINE__ . ' Webhook noticação: ' . $response);
        
        ProcessNotificationPM::dispatch($response);

        return response('Recebido', 200)
            ->header('Content-Type', 'text/plain');
    }
}
