<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class RankingData extends Data
{
    public function __construct(
        public string $name,
        public string $score,
    ) {
    }
}
