<?php

declare(strict_types=1);

namespace PhpDevKits\LaravelCloud\Enums;

/**
 * PHP versions supported by Laravel Cloud
 *
 * @see https://cloud.laravel.com/docs/api/deployments
 */
enum PhpVersion: string
{
    case PHP_82 = '8.2';
    case PHP_83 = '8.3';
    case PHP_84 = '8.4';
}
