<?php

namespace App\Http\Controllers;

use App\Jobs\NotificationSafraJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationSafraController extends Controller
{
    public function index(Request $request)
    {
        // Valida se existe certos itens nos cabeçalhos
        if (
            $request->header('Chargeid') != null
            && $request->header('authorization') != null
            && $request->header('x-aditum-authorization')
        ) {
            NotificationSafraJob::dispatch($request->collect());
            // dd($request->collect()['Transactions'][0]['SoftDescriptor']);
        }

        return response('Recebido', 200)
            ->header('Content-Type', 'text/plain');
    }
}
