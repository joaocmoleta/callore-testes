<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TesteProcessarXMLNF extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teste:processarxmlnfe {--order=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testes processar XML da NFE';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->option('order') == 1) {
            $url = public_path('nfe/20240223092409-nfe-60.xml');
        } else {
            $url = public_path('nfe/20240301093826-nfe-61.xml');
        }

        $data = file_get_contents($url);
        $xml = simplexml_load_string($data);

        $json = json_encode($xml);
        $obj = json_decode($json);

        dd($obj->NFe->infNFe->transp->vol->qVol);

        // if (is_array($obj->NFe->infNFe->det)) {
        //     $this->line(count($obj->NFe->infNFe->det));
        // } else {
        //     $this->line(1);
        // }

        return Command::SUCCESS;
    }
}
