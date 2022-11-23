<?php

namespace App\Actions;

use Sebbmyr\Teams\Cards\SimpleCard;
use Sebbmyr\Teams\TeamsConnector;

class PostMessageInTeamsAction implements Action
{
    public function __construct(
        private readonly string $webhookUrl,
        private readonly string $title,
        private readonly string $text,
    ) {
    }

    public function handle(): void
    {
        $connector = new TeamsConnector($this->webhookUrl);

        $card = new SimpleCard([
            'title' => $this->title,
            'text' => $this->text,
        ]);

        $connector->send($card);
    }
}
