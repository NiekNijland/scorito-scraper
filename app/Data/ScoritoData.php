<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class ScoritoData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $description,
        #[DataCollectionOf(RankingData::class)]
        public DataCollection $rankings,
    ) {
    }

}
