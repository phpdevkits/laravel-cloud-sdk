<?php

declare(strict_types=1);

namespace PhpDevKits\LaravelCloud\Enums;

/**
 * Node.js versions supported by Laravel Cloud
 *
 * @see https://cloud.laravel.com/docs/api/deployments
 */
enum NodeVersion: string
{
    case NODE_20 = '20';
    case NODE_22 = '22';
}
