<?php

namespace App\Actions;

use Exception;

class PostExceptionInTeamsAction implements Action
{
    public function __construct(
        private readonly Exception $exception,
    ) {
    }

    public function handle(): void
    {
        (new PostMessageInTeamsAction(
            webhookUrl: config('teams.incoming_webhook_error_url'),
            title: 'New Exception',
            text: $this->exception->getMessage(),
        ))->handle();
    }
}
