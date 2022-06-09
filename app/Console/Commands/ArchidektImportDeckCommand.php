<?php

namespace App\Console\Commands;

use App\Models\Card;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ArchidektImportDeckCommand extends Command
{
    protected $signature = 'archidekt:import-deck {id} {email}';

    protected $description = 'Import a pre-built deck from https://archideckt.com';

    public function handle()
    {
        $user = User::where('email', $this->argument('email'))->firstOrFail();

        $response = Http::get('https://archidekt.com/api/decks/'.$this->argument('id').'/');

        $deck = $user->decks()->create(['name' => $response->json('name')]);

        $deck->cards()->syncWithPivotValues(Card::where('game', 'mtg')->whereIn('name', (
            collect($response->json('cards'))->pluck('card.oracleCard.name')
        ))->pluck('id'), ['stack' => 'deck']);

        $user->update(['current_deck_id' => $deck->id]);

        return 0;
    }
}
