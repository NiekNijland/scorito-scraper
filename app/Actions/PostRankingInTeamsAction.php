<?php

namespace App\Actions;

use App\Data\ScoritoData;
use Sebbmyr\Teams\Cards\SimpleCard;
use Sebbmyr\Teams\TeamsConnector;

class PostRankingInTeamsAction implements Action
{
    public function __construct(
        private readonly string $webhookUrl,
        private readonly ScoritoData $data
    ) {
    }

    public function handle(): void
    {
        $rankedFirst = $this->data->rankings->first();

        $connector = new TeamsConnector($this->webhookUrl);

        $card  = new SimpleCard([
            'title' => __('teams.title'),
            'text' => __('teams.message', [
                'name' => $rankedFirst->name,
                'points' => $rankedFirst->score,
            ]),
        ]);

        $connector->send($card);
    }
}
