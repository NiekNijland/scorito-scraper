<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class ScoritoData extends Data
{
    public function __construct(
        public string         $id,
        public string         $name,
        public string         $description,
        #[DataCollectionOf(RankingData::class)]
        public DataCollection $rankings,
    )
    {
    }

    public function toString(): string
    {
        $contents = '';
        foreach ($this->rankings as $ranking) {
            $contents .= $ranking->name . ',' . $ranking->score . '|';
        }

        return substr($contents, 0, -1);
    }

    public static function fromString(string $gameId, string $string): self
    {
        $rankingItems = [];

        $rawRankings = explode('|', $string);

        foreach ($rawRankings as $ranking) {
            $values = explode(',' , $ranking);
            $rankingItems[] = new RankingData(
                name: $values[0],
                score: $values[1],
            );
        }

        return new ScoritoData(
            id: $gameId,
            name: 'NAME',
            description: 'DESCRIPTION',
            rankings: RankingData::collection($rankingItems),
        );
    }
}
