<?php

namespace HubSdk;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class HttpApiClient
{
    protected string $apiKey;
    protected string $apiUrl;
    protected Client $httpClient;

    public function __construct(string $apiKey, string $apiUrl)
    {
        $this->apiKey = $apiKey;
        $this->apiUrl = $apiUrl;
        $this->httpClient = new Client();
    }

    public function get(string $endpoint, array $queryParams = []): array
    {
        try {
            $response = $this->httpClient->get($this->buildUrl($endpoint), [
                'headers' => $this->getHeaders(),
                'query' => $queryParams,
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new HubApiException("Error en la API GET: respuesta no fue 200", $response->getStatusCode());
            }

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            throw new HubApiException("Error en la API GET: " . $e->getMessage(), $e->getResponse()->getStatusCode());
        }
    }

    public function post(string $endpoint, array $data = []): array
    {
        try {
            $response = $this->httpClient->post($this->buildUrl($endpoint), [
                'headers' => $this->getHeaders(),
                'json' => $data,
            ]);

            if ($response->getStatusCode() !== 201) {
                throw new HubApiException("Error en la API POST: respuesta no fue 201", $response->getStatusCode());
            }

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            throw new HubApiException("Error en la API POST: " . $e->getMessage(), $e->getResponse()->getStatusCode());
        }
    }

    public function put(string $endpoint, array $data = []): array
    {
        try {
            $response = $this->httpClient->put($this->buildUrl($endpoint), [
                'headers' => $this->getHeaders(),
                'json' => $data,
            ]);

            if ($response->getStatusCode() !== 200 && $response->getStatusCode() !== 204) {
                throw new HubApiException("Error en la API PUT: respuesta no fue 200 o 204", $response->getStatusCode());
            }

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            throw new HubApiException("Error en la API PUT: " . $e->getMessage(), $e->getResponse()->getStatusCode());
        }
    }

    public function delete(string $endpoint): array
    {
        try {
            $response = $this->httpClient->delete($this->buildUrl($endpoint), [
                'headers' => $this->getHeaders(),
            ]);

            if ($response->getStatusCode() !== 204) {
                throw new HubApiException("Error en la API DELETE: respuesta no fue 204", $response->getStatusCode());
            }

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            throw new HubApiException("Error en la API DELETE: " . $e->getMessage(), $e->getResponse()->getStatusCode());
        }
    }

    protected function getHeaders(): array
    {
        return [
            'Authorization' => 'ApiKey ' . $this->apiKey,
            'Accept' => 'application/json',
        ];
    }

    protected function buildUrl(string $endpoint): string
    {
        return rtrim($this->apiUrl, '/') . '/' . ltrim($endpoint, '/');
    }
}
