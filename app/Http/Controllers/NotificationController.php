<?php

namespace App\Http\Controllers;

use App\Jobs\NotificationJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        Log::info(static::class . ' linha ' . __LINE__ . ' Webhook noticação (NotificationController): ' . $request);
        Log::info(static::class . ' linha ' . __LINE__ . ' notificationCode (NotificationController): ' . $request->notificationCode);

        if(isset($request->notificationCode)) {
            NotificationJob::dispatch($request->notificationCode);
        }

        return response('Recebido', 200)
            ->header('Content-Type', 'text/plain');
    }
}
