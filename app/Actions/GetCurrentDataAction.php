<?php

namespace App\Actions;

use App\Data\ScoritoData;
use Illuminate\Support\Facades\Storage;

class GetCurrentDataAction implements Action
{
    public function __construct(
        private readonly string $gameId,
    ) {
    }

    public function handle(): ?ScoritoData
    {
        if (!Storage::exists($this->getFileName())) {
            return null;
        }

        return ScoritoData::fromString(
            $this->gameId,
            Storage::get($this->getFileName())
        );
    }

    private function getFileName(): string
    {
        return $this->gameId . '.txt';
    }
}
