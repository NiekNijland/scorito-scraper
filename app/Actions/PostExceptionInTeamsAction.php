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
            title: 'New Exception',
            text: $this->exception->getMessage(),
        ))->handle();
    }
}
