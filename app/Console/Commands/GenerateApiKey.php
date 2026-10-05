<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('ledger:api-key')]
#[Description('Generate an API key for a consumer app.')]
class GenerateApiKey extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $key = 'ldg_' . Str::random(40);

        $this->info('Give this key to the consumer (shown once, not stored):');
        $this->line($key);
        $this->newLine();
        $this->info('Add its hash to the ledger .env (comma-separate to allow rotation):');
        $this->line('LEDGER_API_KEY_HASHES=' . hash('sha256', $key));

        return self::SUCCESS;
    }
}
