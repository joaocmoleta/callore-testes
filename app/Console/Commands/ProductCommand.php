<?php

namespace App\Console\Commands;

use App\Models\Product as ModelsProduct;
use Illuminate\Console\Command;

class ProductCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:actions {--action=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comandos na ordem. product:actions --action=lista_all';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if($this->option('action') == 'list_all') {
            $products = ModelsProduct::select(
                'products.id',
                'title',
                'value',
                'technical_specifications.sizes',
                'technical_specifications.pack_sizes',
                'technical_specifications.wattage'
                )
            ->leftJoin('technical_specifications', 'technical_specifications.product', '=', 'products.id')
            ->orderBy('title', 'ASC')
            ->get();

            $table = [];

            foreach($products as $product) {
                $tamanhos = json_decode($product->sizes);
                $tamanhos_emb = json_decode($product->pack_sizes);
                $table[] = [
                    'id' => $product->id,
                    'title' => $product->title,
                    'value' => $product->value,
                    'sizes' => "$tamanhos->h*$tamanhos->w*$tamanhos->l cm $tamanhos->we Kg",
                    'pack_sizes' => "$tamanhos_emb->h*$tamanhos_emb->w*$tamanhos_emb->l cm $tamanhos_emb->we Kg",
                    'wattage' => $product->wattage
                ];
            }

            $this->table(['id', 'Título', 'Valor', 'Tamanhos', 'Tamanhos embalagem', 'Potência'], $table);
        }

        if($this->option('action') == 'ger_json') {
            $products = ModelsProduct::select(
                'products.id',
                'title',
                'slug',
                'value',
                'discount',
                'images',
                'qtd'
                )
            ->orderBy('title', 'ASC')
            ->get();

            $this->line(json_encode($products->toArray()));
        }
        return Command::SUCCESS;
    }
}
