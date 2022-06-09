<?php

namespace App\Console\Commands;

use App\Models\Card;
use Illuminate\Console\Command;

class ImagesImportCommand extends Command
{
    protected $signature = 'images:import';

    protected $description = 'Import external card images to a self-hosted cloud storage.';

    public function handle()
    {
        Card::whereNull('internal_image_url')->chunkById(1000, fn ($cards) => (
            $cards->each(fn (Card $card) => $card->importImageUrl())
        ));

        return 0;
    }
}
