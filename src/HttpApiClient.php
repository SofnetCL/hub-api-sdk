<?php

namespace HubSdk;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class HttpApiClient
{
    protected array $headers = [];
    protected Client $httpClient;
    protected string $baseUrl;

    public function __construct(?Client $httpClient = null)
    {
        $this->httpClient = $httpClient ?? new Client();
    }

    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    public function setBaseUrl(string $baseUrl): void
    {
        $this->baseUrl = $baseUrl;
    }

    public function get(string $endpoint, array $queryParams = []): array
    {
        try {
            $response = $this->httpClient->get($this->buildUrl($endpoint), [
                'headers' => $this->getHeaders(),
                'query' => $queryParams,
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new HttpApiException(
                    "API GET ERROR: response was not 200, was: " . $response->getStatusCode(),
                    $response->getStatusCode()
                );
            }

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            // Capturar el request original para incluirlo en la excepción
            $requestData = [
                'method' => 'GET',
                'url' => $this->buildUrl($endpoint),
                'headers' => $this->getHeaders(),
                'queryParams' => $queryParams,
            ];

            throw new HttpApiException(
                "Error en la API GET: " . $e->getMessage(),
                $e->getResponse()->getStatusCode(),
                $requestData,
                $e,
            );
        }
    }

    public function post(string $endpoint, array $data = []): array
    {
        try {
            $response = $this->httpClient->post($this->buildUrl($endpoint), [
                'headers' => $this->getHeaders(),
                'json' => $data,
            ]);

            if ($response->getStatusCode() === 400) {
                return json_decode($response->getBody()->getContents(), true);
            }

            if ($response->getStatusCode() !== 201 && $response->getStatusCode() !== 200) {
                throw new HttpApiException(
                    "API POST ERROR: response was not 201 or 200, was: " . $response->getStatusCode(),
                    $response->getStatusCode()
                );
            }

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            // Capturar el request original para incluirlo en la excepción
            $requestData = [
                'method' => 'POST',
                'url' => $this->buildUrl($endpoint),
                'headers' => $this->getHeaders(),
                'data' => $data,
            ];

            throw new HttpApiException(
                "Error en la API POST: " . $e->getMessage(),
                $e->getResponse()->getStatusCode(),
                $requestData,
                $e,
            );
        }
    }

    public function patch(string $endpoint, array $data = []): array
    {
        try {
            $response = $this->httpClient->patch($this->buildUrl($endpoint), [
                'headers' => $this->getHeaders(),
                'json' => $data,
            ]);

            if ($response->getStatusCode() !== 200 && $response->getStatusCode() !== 204) {
                throw new HttpApiException(
                    "API PATCH ERROR: response was not 200 or 204, was: " . $response->getStatusCode(),
                    $response->getStatusCode()
                );
            }

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            // Capturar el request original para incluirlo en la excepción
            $requestData = [
                'method' => 'PATCH',
                'url' => $this->buildUrl($endpoint),
                'headers' => $this->getHeaders(),
                'data' => $data,
            ];

            throw new HttpApiException(
                "Error en la API PATCH: " . $e->getMessage(),
                $e->getResponse()->getStatusCode(),
                $requestData,
                $e,
            );
        }
    }

    public function delete(string $endpoint): array
    {
        try {
            $response = $this->httpClient->delete($this->buildUrl($endpoint), [
                'headers' => $this->getHeaders(),
            ]);

            if ($response->getStatusCode() !== 204) {
                throw new HttpApiException(
                    "API DELETE ERROR: response was not 204, was: " . $response->getStatusCode(),
                    $response->getStatusCode()
                );
            }

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            // Capturar el request original para incluirlo en la excepción
            $requestData = [
                'method' => 'DELETE',
                'url' => $this->buildUrl($endpoint),
                'headers' => $this->getHeaders(),
            ];

            throw new HttpApiException(
                "Error en la API DELETE: " . $e->getMessage(),
                $e->getResponse()->getStatusCode(),
                $requestData,
                $e,
            );
        }
    }

    protected function getHeaders(): array
    {
        return $this->headers;
    }

    protected function buildUrl(string $endpoint): string
    {
        return rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');
    }
}
