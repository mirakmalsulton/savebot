<?php

namespace App\Modules\Telegram\Clients;

use Exception;
use GuzzleHttp\Client as MainClient;
use GuzzleHttp\Client as DownloadClient;
use GuzzleHttp\Exception\GuzzleException;

readonly class TgClient
{
    public function __construct(private MainClient $mainClient, private DownloadClient $downloadClient)
    {
    }

    /**
     * @throws Exception|GuzzleException
     */
    public function post(string $endpoint, array $data)
    {
        $response = $this->mainClient->post($endpoint, $data);
        $result = json_decode($response->getBody()->getContents(), true);

        if ($result['ok'] !== true) {
            throw new Exception($result['description']);
        }

        return $result['result'];
    }

    public function get(string $endPoint, array $data)
    {
        $response = $this->mainClient->get($endPoint, ['query' => $data]);
        $result = json_decode($response->getBody()->getContents(), true);

        if ($result['ok'] !== true) {
            throw new Exception($result['description']);
        }

        return $result['result'];
    }

    public function download($filePath): string
    {
        $response = $this->downloadClient->get($filePath);
        return $response->getBody()->getContents();
    }
}
