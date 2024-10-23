<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class BallDontLieService
{
    protected $client;
    protected $rateLimit;
    protected $delay;

    public function __construct(Client $client)
    {
        $this->client = $client;

        $this->rateLimit = config('balldontlie.rate_limit');
        $this->delay = 60 / $this->rateLimit;
    }

    public function teams($id = null)
    {
        return $this->makeRequest('get', 'v1/teams/' . $id);
    }

    public function players($cursor = 1, $id = null)
    {
        return $this->makeRequest('get', 'v1/players/' . $id, ['query' => ['per_page' => 100, 'cursor' => $cursor]]);
    }

    public function games($cursor = null, $id = null)
    {
        return $this->makeRequest('get', 'v1/games/' . $id, [
            'query' => [
                'per_page' => 100,
                'seasons[]' => '2023',
                'cursor' => $cursor
            ]
        ]);
    }

    protected function makeRequest($method, $endpoint, $params = [])
    {
        try {
            $response = $this->client->request($method, $endpoint, $params);

            sleep($this->delay);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {

            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : null;

            return match ($statusCode) {
                400 => $this->handleError('Bad Request - The request is invalid. The request parameters are probably incorrect.'),
                401 => $this->handleError('Unauthorized - You either need an API key or your account tier does not have access to the endpoint.'),
                404 => $this->handleError('Not Found - The specified resource could not be found.'),
                406 => $this->handleError('Not Acceptable - You requested a format that isn\'t json.'),
                429 => $this->handleRateLimitExceeded($method, $endpoint, $params),
                500 => $this->handleError('Internal Server Error - We had a problem with our server. Try again later.'),
                503 => $this->handleError('Service Unavailable - We\'re temporarily offline for maintenance. Please try again later.'),
                default => $this->handleError('Erro inesperado: ' . $e->getMessage()),
            };
        }
    }


    protected function handleError($message)
    {
        Log::error($message);
        throw new \Exception($message);
    }

    protected function handleRateLimitExceeded($method, $endpoint, $params)
    {
        Log::warning('Too Many Requests - You\'re rate limited. Waiting to try again...');
        return $this->makeRequest($method, $endpoint, $params);
    }
}
