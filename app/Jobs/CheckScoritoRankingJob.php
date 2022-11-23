<?php

namespace App\Jobs;

use App\Actions\CheckDataIsEqualAction;
use App\Actions\GetCurrentDataAction;
use App\Actions\GetScoritoDataAction;
use App\Actions\PostExceptionInTeamsAction;
use App\Actions\PostRankingInTeamsAction;
use App\Actions\SaveDataAction;
use App\Exceptions\ScoritoApiException;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class CheckScoritoRankingJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly string $gameId)
    {
    }

    /**
     * @throws ScoritoApiException
     */
    public function handle(): void
    {
        $oldData = (new GetCurrentDataAction($this->gameId))->handle();
        $data = (new GetScoritoDataAction($this->gameId))->handle();

        if (is_null($oldData) || ! (new CheckDataIsEqualAction($oldData, $data))->handle()) {
            (new PostRankingInTeamsAction($data))->handle();
        }

        (new SaveDataAction($data))->handle();
    }

    public function failed(Throwable $exception): void
    {
        if ($exception instanceof Exception) {
            (new PostExceptionInTeamsAction($exception))->handle();
        }
    }
}
