<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PagSeguro extends Controller
{
    public function getPublicKey()
    {
        if (env('PAGSEGURO_TOKEN') == '' || env('PAGSEGURO_URL') == '') {
            Log::error(static::class . ' linha ' . __LINE__ . ' Erro nas envioments utilizadas no pagamento (Testes).');
            Log::info(static::class . ' linha ' . __LINE__ . ' PAGSEGURO_TOKEN: ' . env('PAGSEGURO_TOKEN'));
            Log::info(static::class . ' linha ' . __LINE__ . ' PAGSEGURO_URL: ' . env('PAGSEGURO_URL'));
            exit();
        }

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => env('PAGSEGURO_URL') . 'public-keys/card',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . env('PAGSEGURO_TOKEN'),
                "Content-type: application/json",
                "accept: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return response()->json($err, 500);
            // echo "cURL Error #:" . $err;
        } else {
            // echo '<pre>';
            // print_r(json_decode($response));
            return response()->json($response, 200);
        }
    }
}
