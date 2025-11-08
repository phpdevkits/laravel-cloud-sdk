# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a framework-agnostic PHP SDK for the [Laravel Cloud](https://cloud.laravel.com) API. The package is built on top of [Saloon](https://docs.saloon.dev/) for HTTP communication and provides a fluent interface to interact with Laravel Cloud's REST API.

**Target PHP Version**: 8.2+
**Framework**: None (framework-agnostic, can be extended for Laravel, Symfony, WordPress, etc.)
**HTTP Client**: Saloon v3
**API Specification**: JSON:API

## Development Commands

### Testing
```bash
# Run full test suite (lint, type coverage, typos, unit tests, types, refactor checks)
composer test

# Run individual test components
composer test:unit              # PEST unit tests with 100% code coverage requirement
composer test:types             # PHPStan static analysis at max level
composer test:type-coverage     # PEST type coverage - requires exactly 100%
composer test:lint              # Pint formatting check (dry-run)
composer test:typos             # Peck typo checking
composer test:refactor          # Rector refactor check (dry-run)
```

### Code Quality
```bash
composer lint                   # Auto-fix code style with Laravel Pint
composer refactor               # Apply Rector refactoring rules
```

### Running Single Tests
```bash
vendor/bin/pest tests/Arch/LaravelCloudTest.php    # Run specific test file
vendor/bin/pest --filter="test name"               # Run tests matching pattern
```

## Implementation Strategy - Keep It Simple

**One endpoint at a time:**
1. Create the Request class only (with tests)
2. Commit when tests pass
3. THEN add Resource wrapper (with tests)
4. Commit when tests pass
5. THEN add Data classes IF needed for request/response (with tests)
6. Commit when tests pass

**Never:**
- Create fixtures manually - MockClient auto-records them from real API calls
- Implement everything in one go without testing each piece
- Assume what's needed - ask when uncertain
- Continue when confused - stop and ask for clarification
- Add Laravel-specific dependencies or code

**Always:**
- Stop and ask when stuck or uncertain
- Check similar existing code for patterns
- Test each piece independently before moving to the next
- When given a hint, re-read the relevant code to connect the dots before proceeding
- Keep the SDK framework-agnostic (no `config()`, no `Illuminate\*`, no Laravel Collections)

## Architecture

### Saloon Integration

This SDK is built on Saloon, a modern PHP HTTP client abstraction. Key architectural components:

1. **Connector** (`PhpDevKits\LaravelCloud\LaravelCloud`): Main entry point that handles authentication and base URL configuration
   - Bearer token authentication via `Authorization: Bearer <token>` header
   - JSON:API content type (`application/vnd.api+json`)
   - Constructor-based configuration (no Laravel config helper)

2. **Requests**: Individual API endpoint requests extending Saloon's `Request` class
   - Located in `src/Requests/` directory
   - Each request defines HTTP method, endpoint, and request/response DTOs

3. **Resources**: Logical groupings of related requests
   - `Applications`: Application management
   - `Environments`: Environment management
   - `Deployments`: Deployment operations
   - `Domains`: Domain management
   - `Commands`: Command execution
   - `Instances`: Instance management
   - `BackgroundProcesses`: Background process management
   - Located in `src/Resources/` directory

### Framework-Agnostic Design

**IMPORTANT**: This SDK must remain framework-agnostic:
- NO `config()` helper - use constructor injection
- NO `Illuminate\Contracts\Support\Arrayable` - use custom interfaces or plain arrays
- NO Laravel Collections - use standard PHP arrays
- NO Laravel-specific packages - only Saloon and dev tools
- NO Orchestra Testbench - use plain PHPUnit/PEST

**Configuration Pattern**:
```php
// Good - constructor injection
$cloud = new LaravelCloud(
    apiToken: 'your-token',
    baseUrl: 'https://app.laravel.cloud/api'
);

// Bad - Laravel config helper
$cloud = new LaravelCloud(
    apiToken: config('cloud.api_token')
);
```

### Laravel Cloud API Endpoints

The SDK targets the Laravel Cloud API:
- **Base URL**: `https://app.laravel.cloud/api`
- **Authentication**: Bearer token in `Authorization` header
- **Content Type**: `application/vnd.api+json` (JSON:API specification)

Refer to [Laravel Cloud API Documentation](https://cloud.laravel.com/docs/api/introduction) for endpoint details.

### Laravel Cloud API Documentation

Laravel Cloud API endpoint documentation is stored locally in the `.ai/laravel-cloud/` directory:
- Documentation is organized by resource/entity (e.g., `applications/`, `environments/`, `deployments/`)
- Each endpoint has its own markdown file within the resource subfolder
- File naming follows the endpoint name (e.g., `list.md`, `get.md`, `create.md`)
- Example structure:
  ```
  .ai/laravel-cloud/
  ├── applications/
  │   ├── list.md
  │   └── get.md
  ├── environments/
  │   ├── list.md
  │   ├── get.md
  │   └── create.md
  └── deployments/
      ├── list.md
      └── get.md
  ```

When implementing or modifying SDK endpoints, always refer to the corresponding documentation file in `.ai/laravel-cloud/` for accurate API specifications, request/response formats, and field definitions.

## Code Quality Standards

### Code Style Conventions

**Enum Definitions**:
- Enum cases should NOT include individual PHPDoc comments
- Use a single class-level PHPDoc comment describing the enum's purpose
- Example:
  ```php
  /**
   * AWS regions available for Laravel Cloud applications
   *
   * @see https://cloud.laravel.com/docs/api/applications
   */
  enum Region: string
  {
      case US_EAST_1 = 'us-east-1';
      case US_EAST_2 = 'us-east-2';
  }
  ```

### PHPStan Configuration
- **Level**: max
- **Scope**: `src/` directory only
- Reports unmatched ignored errors
- Enforces exception documentation with `@throws`

### Rector Rules
Applies the following preset rule sets to `src/` and `tests/`:
- Dead code elimination
- Code quality improvements
- Type declarations
- Privatization
- Early returns
- Strict booleans
- PHP version-specific improvements

**Exception**: Skips `AddOverrideAttributeToOverriddenMethodsRector`

### Test Coverage Requirements
- **Code coverage**: Exactly 100% required
- **Type coverage**: Exactly 100% required
- Tests written in PEST framework
- Lawman for architecture testing

### PEST Test Assertions
- **Chain expectations**: Multiple expectations should be chained together using `->and()` for cleaner, more readable tests
- **Example**:
  ```php
  // Good - chained expectations
  expect($response->status())->toBe(200)
      ->and($response->json()['data'])->toHaveKey('id')
      ->and($response->json()['data']['type'])->toBe('applications');

  // Bad - separate expect() calls
  expect($response->status())->toBe(200);
  expect($response->json()['data'])->toHaveKey('id');
  expect($response->json()['data']['type'])->toBe('application');
  ```

### Peck Typo Checking
Ignored words: php, laravel, cloud, sdk, saloon, api

## Package Namespace

All code lives under the `PhpDevKits\LaravelCloud` namespace, following PSR-4 autoloading:
- `src/` → `PhpDevKits\LaravelCloud\`
- `tests/` → `Tests\`

## Git and Version Control

### Commit Messages
- Write clear, concise commit messages describing the change
- Use conventional commit format when appropriate (feat:, fix:, refactor:, etc.)
- Focus commit messages on "why" rather than "what"

### Pull Requests
- Draft clear PR descriptions summarizing the changes
- Include relevant context and motivation
- List breaking changes if any
- Add test plan or verification steps
- Reference related issues when applicable

## Important Implementation Notes

When implementing new Laravel Cloud API endpoints:

1. Create a new Request class in `src/Requests/` extending Saloon's `Request`
2. Define the HTTP method, endpoint path, and configure request/response handling
3. Group related requests into Resource classes in `src/Resources/`
4. Ensure 100% test coverage with PEST tests in `tests/`
5. Use type-safe arrays and DTOs for request/response data
6. Follow Saloon best practices for authentication, middleware, and error handling
7. Support JSON:API response format and relationships

### Exception Documentation with @throws

**PHPDoc @throws Annotations:**
All methods that can throw exceptions MUST document them with `@throws` annotations. This is automatically enforced by PHPStan for source code.

**For Source Code (`src/`):**
- Resource methods calling `$this->connector->send()` MUST have `@throws Throwable`
- Any method that can throw exceptions MUST document them
- PHPStan enforces this automatically via `exceptions.check.missingCheckedExceptionInThrows: true`

**For Test Code (`tests/`):**
- Test functions calling Resource methods MUST have `@throws Throwable`
- Test functions calling `$cloud->send()` MUST have `@throws Throwable`
- Annotation placed in PHPDoc between test name and function declaration
- **NOT automatically enforced** - relies on code review

**Example (Source Code):**
```php
/**
 * List all applications
 *
 * @param  array<string, mixed>  $filters
 * @throws Throwable
 */
public function list(array $filters = []): Response
{
    return $this->connector->send(
        request: new ListApplications(filters: $filters),
    );
}
```

**Example (Test Code):**
```php
test('lists applications',
    /**
     * @throws Throwable
     */
    function (): void {
        $mockClient = new MockClient([
            ListApplications::class => MockResponse::fixture('applications/list'),
        ]);

        $response = $cloud
            ->withMockClient($mockClient)
            ->send(new ListApplications());

        expect($response->status())->toBe(200);
    });
```

### Resource Class Organization

**Method Ordering**:
- **ALWAYS organize Resource class methods in alphabetical order**
- This improves code readability and makes methods easier to find
- Applies to all Resource classes in `src/Resources/`

### Testing with Saloon MockClient

Tests use Saloon's `MockClient` to mock HTTP responses:

```php
$mockClient = new MockClient([
    ListApplications::class => MockResponse::fixture('applications/list'),
]);

$response = $cloud
    ->withMockClient($mockClient)
    ->send(new ListApplications());
```

**Fixture Auto-Recording**: When a fixture doesn't exist, MockClient automatically:
1. Makes a real API call to Laravel Cloud
2. Records the response as a JSON fixture in `tests/Fixtures/Saloon/`
3. Uses the recorded fixture for subsequent test runs

**IMPORTANT**:
- **NEVER create fixture files manually**
- Always let Saloon's MockClient auto-record fixtures from real API calls
- If API calls fail during fixture recording, fix the test data or API configuration

**Test Organization**:
- Tests related to Request classes should be placed in `tests/Unit/Requests/` directory
- New tests should be placed **after** `beforeEach`/`afterEach` hooks but **before** older tests
- Newest tests first, oldest tests last (reverse chronological order)
- Use descriptive test names in snake_case
- Fixture names should match test purpose
- **ALWAYS use enums in tests** instead of magic strings

### Lawman Architecture Testing

Use Lawman for comprehensive API architecture validation:

```php
test('connector')
    ->expect(LaravelCloud::class)
    ->toBeSaloonConnector()
    ->toUseAcceptsJsonTrait();

test('application requests')
    ->expect('PhpDevKits\LaravelCloud\Requests\Application')
    ->toBeSaloonRequest()
    ->toUseStrictTypes();
```

**Lawman Expectations**:
- `toBeSaloonConnector()` - Validates connector extends Saloon\Http\Connector
- `toBeSaloonRequest()` - Validates request extends Saloon\Http\Request
- `toSendGetRequest()`, `toSendPostRequest()`, etc. - HTTP method validation
- `toHaveJsonBody()` - JSON request body validation
- `toUseAcceptsJsonTrait()` - Trait usage validation
- `toUseStrictTypes()` - Strict typing validation

## JSON:API Specification

Laravel Cloud API follows JSON:API specification:

**Response Structure**:
```json
{
  "data": {...},
  "included": [...],
  "links": {...},
  "meta": {...}
}
```

**Pagination**:
- `links`: first, last, prev, next URLs
- `meta`: current_page, total, per_page, from, to

**Relationships**:
- Resources include relationships to related entities
- Use `include` query parameter to load relationships

## Framework-Agnostic Extensions

While this SDK is framework-agnostic, framework-specific extensions can be created separately:
- `phpdevkits/laravel-cloud-laravel` - Laravel ServiceProvider, Facade, config
- `phpdevkits/laravel-cloud-symfony` - Symfony Bundle
- `phpdevkits/laravel-cloud-wordpress` - WordPress plugin

These extensions should depend on this core SDK and add framework-specific conveniences.
