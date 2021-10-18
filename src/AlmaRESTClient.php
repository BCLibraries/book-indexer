<?php

namespace Bclib\GetBooksFromAlma;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception as HTTPClientException;

class AlmaRESTClient
{
    private HttpClientInterface $http_client;
    private string $apikey;
    private string $region;

    public function __construct(HttpClientInterface $http_client, string $region, string $apikey)
    {
        $this->http_client = $http_client;
        $this->apikey = $apikey;
        $this->region = $region;
    }

    public function buildLocationRepository(): LocationRepository
    {
        $location_repository = new LocationRepository();
        $libraries = $this->fetchLibraries();
        foreach ($libraries as $library) {
            foreach ($this->fetchLocations($library) as $location) {
                $location_repository->addLocation($location);
            }
        }
        return $location_repository;
    }

    private function fetchLocations(Library $library): \Generator
    {
        $query_params = [
            'apikey' => $this->apikey
        ];
        $sub_path = "conf/libraries/{$library->getCode()}/locations";
        $api_response = $this->makeRequest($sub_path, $query_params);

        $api_response['location'] = $api_response['location'] ?? [];

        foreach ($api_response['location'] as $location_json) {
            yield new Location($location_json['code'], $location_json['name'], $location_json['external_name'], $library);
        }
    }

    /**
     * @return Library[]
     * @throws HTTPClientException\ClientExceptionInterface
     * @throws HTTPClientException\DecodingExceptionInterface
     * @throws HTTPClientException\RedirectionExceptionInterface
     * @throws HTTPClientException\ServerExceptionInterface
     * @throws HTTPClientException\TransportExceptionInterface
     */
    private function fetchLibraries(): array
    {
        $query_params = [
            'apikey' => $this->apikey
        ];
        $api_response = $this->makeRequest('conf/libraries', $query_params);

        return array_map(function ($library_json) {
            return new Library($library_json['code'], $library_json['name']);
        }, $api_response['library']);
    }

    /**
     * @param string $sub_path
     * @param string[] $query_params
     * @return array
     * @throws HTTPClientException\ClientExceptionInterface
     * @throws HTTPClientException\DecodingExceptionInterface
     * @throws HTTPClientException\RedirectionExceptionInterface
     * @throws HTTPClientException\ServerExceptionInterface
     * @throws HTTPClientException\TransportExceptionInterface
     */
    private function makeRequest(string $sub_path, array $query_params): array
    {
        // Sleep .3 seconds because we don't want to hammer the API.
        usleep(300000);
        $url = $this->buildUrl($sub_path, $query_params);
        echo "Requesting $url\n";
        return $this->http_client->request('GET', $url, [
            'headers' => [
                'Accept' => 'application/json',
            ]
        ])->toArray();

    }

    private function buildUrl(string $sub_path, array $query_params): string
    {
        $host = "api-{$this->region}.hosted.exlibrisgroup.com";
        $query_string = http_build_query($query_params);
        return "https://$host/almaws/v1/$sub_path?$query_string";
    }
}