<div class="filament-hidden">

![Laravel OneSignal](https://raw.githubusercontent.com/jeffersongoncalves/laravel-onesignal/main/art/jeffersongoncalves-laravel-onesignal.png)

</div>

# Laravel OneSignal

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-onesignal.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-onesignal)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-onesignal/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/laravel-onesignal/actions?query=workflow%3ATests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-onesignal/pint.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/laravel-onesignal/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-onesignal.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-onesignal)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/laravel-onesignal.svg?style=flat-square)](LICENSE.md)

A thin Laravel wrapper for the [OneSignal REST API](https://api.onesignal.com). Covers notifications, segments, users and templates through a simple, typed API built on Laravel's `Http` client.

## Features

- Notifications: `send`, `list`, `get`, `cancel`
- Segments: `list`, `create`, `delete`
- Users: `get`, `create`, `delete`
- Templates: `list`, `get`, `create`
- App: `get`
- Notification targeting precedence: segments > emails > player IDs > aliases > default `Subscribed Users` segment
- Throws `OneSignalException` (with the original API error body) on any non-2xx response

## Installation

You can install the package via composer:

```bash
composer require jeffersongoncalves/laravel-onesignal
```

Publish the config file:

```bash
php artisan vendor:publish --tag=laravel-onesignal-config
```

Set your OneSignal App ID and REST API key in `.env`:

```env
ONESIGNAL_APP_ID=your-app-id
ONESIGNAL_REST_API_KEY=your-rest-api-key
```

Both are found under **Settings > Keys & IDs** in your OneSignal dashboard.

## Configuration

```php
// config/laravel-onesignal.php
return [
    'app_id' => env('ONESIGNAL_APP_ID', ''),
    'rest_api_key' => env('ONESIGNAL_REST_API_KEY', ''),
];
```

## Usage

The package is resolved via the `OneSignal` facade or by injecting `JeffersonGoncalves\LaravelOnesignal\OneSignal`. Each resource is exposed as a method returning a dedicated resource class.

### Notifications

```php
use JeffersonGoncalves\LaravelOnesignal\Facades\OneSignal;

// Defaults to the "Subscribed Users" segment
OneSignal::notifications()->send('Hello world!', heading: 'Hi there');

// Target specific segments
OneSignal::notifications()->send('Hello!', segments: ['Active Users']);

// Target specific emails, player IDs or aliases
OneSignal::notifications()->send('Hello!', emails: ['jane@example.com']);
OneSignal::notifications()->send('Hello!', playerIds: ['player-id']);
OneSignal::notifications()->send('Hello!', aliases: ['external_id' => ['user-1']], channel: 'push');

$notifications = OneSignal::notifications()->list(limit: 10, offset: 0);

$notification = OneSignal::notifications()->get('notification-id');

OneSignal::notifications()->cancel('notification-id');
```

Targeting precedence when multiple options are given: `segments` > `emails` > `playerIds` > `aliases` > the default `Subscribed Users` segment.

### Segments

```php
$segments = OneSignal::segments()->list(limit: 10, offset: 0);

// Default filter: session_count > 0
OneSignal::segments()->create('Active Users');

OneSignal::segments()->create('VIPs', [
    ['field' => 'tag', 'key' => 'vip', 'relation' => '=', 'value' => 'true'],
]);

OneSignal::segments()->delete('segment-id');
```

### Users

```php
$user = OneSignal::users()->get('user-1');
$user = OneSignal::users()->get('onesignal-id', 'onesignal_id');

OneSignal::users()->create(externalId: 'user-1', email: 'jane@example.com', tags: ['plan' => 'pro']);

OneSignal::users()->delete('user-1');
```

### Templates

```php
$templates = OneSignal::templates()->list(limit: 10, offset: 0);

$template = OneSignal::templates()->get('template-id');

OneSignal::templates()->create('Welcome', message: 'Hi there!', heading: 'Welcome!');
```

### App

```php
$app = OneSignal::app()->get();
```

### Error handling

Any non-2xx API response throws `JeffersonGoncalves\LaravelOnesignal\Exceptions\OneSignalException`, which exposes the decoded error body:

```php
use JeffersonGoncalves\LaravelOnesignal\Exceptions\OneSignalException;

try {
    OneSignal::notifications()->send('Hello!');
} catch (OneSignalException $e) {
    logger()->error($e->getMessage(), $e->errorBody());
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Jefferson Gonçalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
