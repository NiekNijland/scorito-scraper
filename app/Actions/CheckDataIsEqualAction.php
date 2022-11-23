<?php

namespace App\Actions;

use App\Data\RankingData;
use App\Data\ScoritoData;

class CheckDataIsEqualAction implements Action
{
    public function __construct(
        private readonly ScoritoData $data1,
        private readonly ScoritoData $data2,
    ) {
    }

    public function handle(): bool
    {
        $i = 0;
        foreach ($this->data1->rankings as $ranking1) {
            if (!$this->rankingsAreEqual($ranking1, $this->data2->rankings[$i])) {
                return false;
            }

            $i++;
        }

        return true;
    }

    private function rankingsAreEqual(RankingData $ranking1, RankingData $ranking2): bool
    {
        $equalName = $ranking1->name === $ranking2->name;
        $equalScore = $ranking1->score === $ranking2->score;

        return $equalName && $equalScore;
    }
}
