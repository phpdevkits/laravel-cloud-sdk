<?php

declare(strict_types=1);

namespace PhpDevKits\LaravelCloud;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

class LaravelCloud extends Connector
{
    use AcceptsJson;

    /**
     * @param  string  $apiToken  The Laravel Cloud API token
     * @param  string  $baseUrl  The base URL for the Laravel Cloud API
     */
    public function __construct(
        private readonly string $apiToken,
        private readonly string $baseUrl = 'https://app.laravel.cloud/api',
    ) {}

    /**
     * The Base URL of the API
     */
    public function resolveBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Default headers for every request
     *
     * @return array<string, string>
     */
    protected function defaultHeaders(): array
    {
        return [
            'Authorization' => 'Bearer '.$this->apiToken,
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json',
        ];
    }
}
