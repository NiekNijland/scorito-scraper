<?php

namespace App\Actions;

use App\Data\RankingData;
use App\Data\ScoritoData;
use App\Exceptions\ScoritoApiException;
use JsonException;

class GetScoritoDataAction implements Action
{
    public function __construct(
        private readonly string $gameId
    ) {
    }

    /**
     * @throws ScoritoApiException
     */
    public function handle(): ScoritoData
    {
        $response = $this->makeRequest();

        return $this->parseResponse($response);
    }

    /**
     * @throws ScoritoApiException
     */
    private function makeRequest(): array
    {
        $ch = curl_init();

        curl_setopt(
            $ch,
            CURLOPT_URL,
            'https://ranking.scorito.com/7/ranking/v2.0/gameranking/getpage/' . $this->gameId . '/0/0'
        );

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders());

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new ScoritoApiException(curl_error($ch));
        }

        curl_close($ch);

        try {
            $response = json_decode($result, true, 512, JSON_THROW_ON_ERROR);
            if (! isset($response['Content']['RankingItems'])) {
                throw new JsonException();
            }
        } catch (JsonException $e) {
            throw new ScoritoApiException($e->getMessage());
        }

        return $response;
    }

    private function parseResponse(array $response): ScoritoData
    {
        $rankingDataItems = [];
        foreach ($response['Content']['RankingItems'] as $rawRanking) {
            $rankingDataItems[] = new RankingData(
                name: $rawRanking['UserName'],
                score: $rawRanking['TotalPoints'],
            );
        }

        return new ScoritoData(
            id: $this->gameId,
            name: 'NAME',
            description: 'DESCRIPTION',
            rankings: RankingData::collection($rankingDataItems),
        );
    }

    private function getHeaders(): array
    {
        return [
            'Authority: ranking.scorito.com',
            'Accept: application/json',
            'Accept-Language: en-GB,en;q=0.7',
            'Cache-Control: no-cache',
            'Origin: https://mobile.scorito.com',
            'Pragma: no-cache',
            'Referer: https://mobile.scorito.com/',
            'Sec-Fetch-Dest: empty',
            'Sec-Fetch-Mode: cors',
            'Sec-Fetch-Site: same-site',
            'Sec-Gpc: 1',
            'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36',
        ];
    }
}
