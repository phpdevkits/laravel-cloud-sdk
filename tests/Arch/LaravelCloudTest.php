<?php

declare(strict_types=1);

use PhpDevKits\LaravelCloud\LaravelCloud;

test('connector')
    ->expect(LaravelCloud::class)
    ->toBeSaloonConnector()
    ->toHaveDefaultHeaders()
    ->toHaveDefaultConfig()
    ->toHaveBaseUrl()
    ->toUseAcceptsJsonTrait();

test('can be instantiated', function (): void {
    $cloud = new LaravelCloud(apiToken: 'test-token');

    expect($cloud)
        ->toBeInstanceOf(LaravelCloud::class);
});

test('resolves base URL with default', function (): void {
    $cloud = new LaravelCloud(apiToken: 'test-token');

    expect($cloud->resolveBaseUrl())
        ->toBe('https://app.laravel.cloud/api');
});

test('resolves base URL with custom', function (): void {
    $cloud = new LaravelCloud(
        apiToken: 'test-token',
        baseUrl: 'https://custom.laravel.cloud/api'
    );

    expect($cloud->resolveBaseUrl())
        ->toBe('https://custom.laravel.cloud/api');
});

test('returns default headers with bearer token', function (): void {
    $cloud = new LaravelCloud(apiToken: 'test-api-token');

    $headers = $cloud->headers()->all();

    expect($headers)
        ->toHaveKey('Authorization', 'Bearer test-api-token')
        ->toHaveKey('Accept', 'application/vnd.api+json')
        ->toHaveKey('Content-Type', 'application/vnd.api+json');
});

test('returns empty default config', function (): void {
    $cloud = new LaravelCloud(apiToken: 'test-token');

    $config = $cloud->config()->all();

    expect($config)->toBe([]);
});
