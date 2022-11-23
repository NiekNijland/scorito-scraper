<?php

namespace App\Console\Commands;

use App\Jobs\CheckScoritoRankingJob;
use Illuminate\Console\Command;

class ManualTestCommand extends Command
{
    protected $signature = 'manual:test';

    public function handle(): void
    {
        CheckScoritoRankingJob::dispatch(config('scorito.game_id'));
    }
}
