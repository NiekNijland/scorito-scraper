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
            'text' => $this->getMessageText(),
        ]);

        $connector->send($card);
    }

    private function getMessageText(): string
    {
        $text = '';
        for ($i = 0; $i < 5; $i++) {
            $ranking = $this->data->rankings[$i];

            $text .= __('teams.message', [
                'place' => $i + 1,
                'name' => $ranking->name,
                'points' => $ranking->score,
            ]) . "<br>";
        }

        $text .= '<br><b><a href="https://github.com/NiekNijland/scorito-scraper">View sourcecode</a></b>';

        return $text;
    }
}
