# Laravel Cloud SDK

A modern, framework-agnostic PHP SDK for the [Laravel Cloud](https://cloud.laravel.com) API.

Built on [Saloon](https://docs.saloon.dev/) for robust HTTP communication with full type safety and modern PHP 8.4+ features.

## Features

- **Framework Agnostic** - Pure PHP SDK with no Laravel dependencies
- **Fully Typed** - Complete type safety with PHP 8.4+ strict types
- **Modern Enums** - Backed enums for all API constants
- **JSON:API Support** - Native support for JSON:API specification
- **100% Test Coverage** - Comprehensive PEST test suite
- **Architecture Tested** - Lawman integration for API architecture validation

## Requirements

- PHP 8.2 or higher
- Composer

## Installation

```bash
composer require phpdevkits/laravel-cloud-sdk
```

## Quick Start

### Authentication

First, create an API token in your Laravel Cloud organization settings:

1. Navigate to your organization settings
2. Go to "API tokens" section
3. Create a new token (1 month, 6 months, or 1 year expiration)
4. Copy the token (displayed only once)

### Basic Usage

```php
use PhpDevKits\LaravelCloud\LaravelCloud;

// Initialize the SDK
$cloud = new LaravelCloud(
    apiToken: 'your-api-token-here'
);

// The SDK is now ready to use
// Resources will be added as the SDK evolves
```

### Custom Base URL

If you need to use a different API endpoint:

```php
$cloud = new LaravelCloud(
    apiToken: 'your-api-token-here',
    baseUrl: 'https://custom.laravel.cloud/api'
);
```

## Available Enums

The SDK provides type-safe enums for common values:

```php
use PhpDevKits\LaravelCloud\Enums\Region;
use PhpDevKits\LaravelCloud\Enums\PhpVersion;
use PhpDevKits\LaravelCloud\Enums\NodeVersion;

// AWS Regions
Region::US_EAST_1;
Region::US_EAST_2;
Region::EU_CENTRAL_1;
Region::EU_WEST_1;
Region::EU_WEST_2;
Region::AP_SOUTHEAST_1;
Region::AP_SOUTHEAST_2;

// PHP Versions
PhpVersion::PHP_82;
PhpVersion::PHP_83;
PhpVersion::PHP_84;

// Node.js Versions
NodeVersion::NODE_20;
NodeVersion::NODE_22;
```

## Development

### Setup

```bash
# Install dependencies
composer install

# Run tests
composer test

# Individual test commands
composer test:unit              # PEST unit tests with 100% coverage
composer test:types             # PHPStan static analysis
composer test:type-coverage     # Type coverage at 100%
composer test:lint              # Code style check
composer test:typos             # Typo checking
composer test:refactor          # Rector refactoring check
```

### Code Quality

```bash
# Auto-fix code style
composer lint

# Apply refactoring rules
composer refactor
```

## API Documentation

The Laravel Cloud API is currently in Early Access. For complete API documentation, visit:

[https://cloud.laravel.com/docs/api/introduction](https://cloud.laravel.com/docs/api/introduction)

## Roadmap

This SDK is in active development. Planned features:

- [ ] Applications resource
- [ ] Environments resource
- [ ] Deployments resource
- [ ] Domains resource
- [ ] Commands resource
- [ ] Instances resource
- [ ] Background Processes resource
- [ ] Laravel integration package (optional)
- [ ] Symfony integration package (optional)

## Contributing

Contributions are welcome! Please ensure:

- All tests pass (`composer test`)
- Code follows PSR-12 standards
- 100% code and type coverage maintained
- PHPStan level max passes

## License

MIT License. See [LICENSE](LICENSE) for details.

## Credits

- Built by [Francisco Barrento](https://github.com/franciscobarrento)
- Powered by [Saloon](https://docs.saloon.dev/)
- Architecture testing by [Lawman](https://github.com/jonpurvis/lawman)

## Support

For issues and feature requests, please use the [GitHub issue tracker](https://github.com/phpdevkits/laravel-cloud-sdk/issues).
