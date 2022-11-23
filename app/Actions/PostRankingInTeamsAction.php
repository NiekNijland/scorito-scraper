<?php

namespace App\Actions;

use App\Data\ScoritoData;

class PostRankingInTeamsAction implements Action
{
    public function __construct(private readonly ScoritoData $data)
    {
    }

    public function handle(): void
    {
        (new PostMessageInTeamsAction(
            webhookUrl: config('teams.incoming_webhook_url'),
            title: __('teams.title'),
            text: $this->getMessageText(),
        ))->handle();
    }

    private function getMessageText(): string
    {
        $text = '';

        $iMax = $this->data->rankings->count() > 10
            ? 10
            : $this->data->rankings->count();

        for ($i = 0; $i < $iMax; $i++) {
            $ranking = $this->data->rankings[$i];

            $text .= __('teams.message', [
                'place' => $i + 1,
                'name' => $ranking->name,
                'points' => $ranking->score,
            ]) . '<br>';
        }

        $text .= '<br><b><a href="https://github.com/NiekNijland/scorito-scraper">View sourcecode</a></b>';

        return $text;
    }
}
