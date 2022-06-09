<?php

namespace App\Console\Commands;

use App\Models\Card;
use Illuminate\Console\Command;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class ArchidektImportCardsCommand extends Command
{
    protected $signature = 'archidekt:import-cards';

    protected $description = 'Download card database from https://archidekt.com';

    public function handle()
    {
        $chunks = collect()
            ->times(floor(24191 / 40), fn ($page) => $this->buildUrl($page))
            ->chunk(10);

        foreach ($chunks as $chunk) {
            $results = collect(Http::pool(fn (Pool $pool) => (
                $chunk->map(fn ($url) => $pool->get($url))->all()
            )))->sum(fn (Response $response) => (
                $this->insertCards($response->json('results'))
            ));

            $this->info(now()->toDateTimeString().' - Crawled '.$results.' items');
        }
    }

    public function buildUrl($page)
    {
        return 'https://archidekt.com/api/cards/?'.http_build_query([
            'colors' => 'White,Blue,Black,Red,Green,Colorless',
            'orderBy' => 'oracleCard__name',
            'rarity' => 'common,uncommon,rare,mythic,special',
            'game' => 1,
            'page' => $page,
            'unique' => '',
        ]);
    }

    protected function insertCards($results)
    {
        return Card::insertOrIgnore(
            collect($results)->map(fn ($result) => [
                'game' => 'mtg',
                'name' => data_get($result, 'oracleCard.name'),
                'description' => data_get($result, 'oracleCard.text'),
                'external_image_url' => str('https://c1.scryfall.com/file/scryfall-cards/normal/front')
                    ->append('/'.data_get($result, 'uid')[0])
                    ->append('/'.data_get($result, 'uid')[1])
                    ->append('/'.data_get($result, 'uid').'.jpg?01')
                    ->__toString(),
                'meta' => json_encode(array_merge(
                    Arr::except(data_get($result, 'oracleCard'), ['name', 'text']),
                    Arr::only($result, ['rarity', 'edition', 'artist'])
                )),
                'created_at' => now()->toDateTimeString(),
                'updated_At' => now()->toDateTimeString(),
            ])->all()
        );
    }
}
