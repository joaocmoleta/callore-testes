<?php

namespace App\Http\Controllers;

use App\Jobs\SolicitarColeta;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NotaFiscalController extends Controller
{
    public function saveFile(Request $request)
    {
        $request->validate(
            [
                'file' => 'required|mimes:application/xml,xml',
            ],
            [
                'file' => 'Selecione um arquivo em XML.',
            ]
        );

        $file = $request->file('file');

        // Move Uploaded File
        $slug_name = Str::slug(Carbon::now()->format('YmdHis') . ' nfe ' . $request->order);
        $ext = $file->getClientOriginalExtension();

        // $path = $file->move('nfe', $slug_name . '.' . $ext);
        $path = $file->move(storage_path('app/nfe'), "$slug_name.$ext");

        // Processar nota para retirar dados
        $url = $path;
        $data = file_get_contents($url);
        $xml = simplexml_load_string($data);

        $json = json_encode($xml);
        $obj = json_decode($json);

        // dd($obj->protNFe->infProt->chNFe);

        if (!isset($obj->NFe->infNFe->ide->nNF)) {
            return redirect()->back()->with('error', 'XML inválido.');
        }

        // Salvar no banco de dados a nota
        $save = Invoice::create([
            'order' => $request->order,
            'numero' => $obj->NFe->infNFe->ide->nNF,
            'serie' => $obj->NFe->infNFe->ide->serie,
            'data_emissao' => Carbon::parse($obj->NFe->infNFe->ide->dhEmi)->format('Y-m-d'),
            'natureza' => $obj->NFe->infNFe->ide->natOp,
            'total' => $obj->NFe->infNFe->total->ICMSTot->vNF,
            'produto' => $obj->NFe->infNFe->total->ICMSTot->vProd,
            'chave' => $obj->protNFe->infProt->chNFe,
            'file' => storage_path("app/nfe/$slug_name.$ext"),
            'volumes' => $obj->NFe->infNFe->transp->vol->qVol,
        ]);

        if (!$save) {
            return redirect()->back()->with('error', 'Falha ao salvar nota fiscal.');
        }

        // Agendar job para solicitar retirada da total express
        SolicitarColeta::dispatch($request->order);

        // mudar status pedido
        OrderEvent::create([
            'order' => $request->order,
            'status' => 'nf_add'
        ]);

        Order::select('id')->where('id', $request->order)->first()->update(['status' => 'nf_add']);

        $current_user = $request->user();

        Log::info(static::class . ' linha ' . __LINE__ . " Nota fiscal gravada no sistema em: $path. Adicionada pelo usuário id $current_user->id.");

        return redirect()->back()->with('success', 'Arquivo adicionado. Entrar contato com o suporte do ecommerce.');
    }
}
