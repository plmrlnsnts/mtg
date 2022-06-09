<?php

namespace App\Console\Commands;

use App\Models\Card;
use Illuminate\Console\Command;

class ImportCardImagesCommand extends Command
{
    protected $signature = 'import-card-images';

    protected $description = 'Import external images to a self-hosted cloud storage.';

    public function handle()
    {
        Card::whereNull('internal_image_url')->chunkById(1000, fn ($cards) => (
            $cards->each->importExternalImageUrl()
        ));

        return 0;
    }
}
