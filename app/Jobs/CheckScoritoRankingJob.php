<?php

namespace App\Jobs;

use App\Actions\GetScoritoRankingAction;
use App\Actions\PostRankingInTeamsAction;
use App\Exceptions\ScoritoApiException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckScoritoRankingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly string $webhookUrl,
        private readonly string $gameId,
    ) {
    }

    /**
     * @throws ScoritoApiException
     */
    public function handle(): void
    {
        $rankings = (new GetScoritoRankingAction($this->gameId))->handle();

        (new PostRankingInTeamsAction(
            $this->webhookUrl,
            $rankings,
        ))->handle();
    }

    public function failed(): void
    {

    }
}
