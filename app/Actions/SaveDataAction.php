<?php

namespace App\Actions;

use App\Data\ScoritoData;
use Illuminate\Support\Facades\Storage;

class SaveDataAction implements Action
{
    public function __construct(
        private readonly ScoritoData $data,
    ) {
    }

    public function handle(): void
    {
        Storage::put($this->getFileName(), $this->data->toString());
    }

    public function getFileName(): string
    {
        return $this->data->id . '.txt';
    }
}
