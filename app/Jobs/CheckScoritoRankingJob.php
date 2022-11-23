<?php

namespace App\Jobs;

use App\Actions\CheckDataIsEqualAction;
use App\Actions\GetCurrentDataAction;
use App\Actions\GetScoritoRankingAction;
use App\Actions\PostRankingInTeamsAction;
use App\Actions\SaveDataAction;
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
        $oldData = (new GetCurrentDataAction($this->gameId))->handle();
        $data = (new GetScoritoRankingAction($this->gameId))->handle();

        if (is_null($oldData) || !(new CheckDataIsEqualAction($oldData, $data))->handle()) {
            (new PostRankingInTeamsAction(
                $this->webhookUrl,
                $data,
            ))->handle();
        }

        (new SaveDataAction($data))->handle();
    }

    public function failed(): void
    {

    }
}
