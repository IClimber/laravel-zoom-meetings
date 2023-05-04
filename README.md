# Create Zoom Meetings

[![Latest Version on Packagist]](https://packagist.org/packages/iclimber/laravel-zoom-meetings)

With this package you can create Zoom meetings from your Laravel application using Server-To-Server OAuth.

## Installation

You can install the package via composer:

```bash
composer require iclimber/laravel-zoom-meetings
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="zoom-meetings-config"
```

This is the contents of the published config file:

```php
return [
    'account_id' => env('ZOOM_ACCOUNT_ID'),
    'client_id' => env('ZOOM_CLIENT_ID'),
    'client_secret' => env('ZOOM_CLIENT_SECRET'),
    'base_url' => 'https://api.zoom.us/v2/',
    'token_url' => 'https://zoom.us/oauth/token',
];
```

## Preparing your Zoom account

Create a Server-to-Server OAuth app in your Zoom account following this
instruction: https://developers.zoom.us/docs/internal-apps/create/.
You will need the `user:read:admin meeting:write:admin` scopes.

Save the Account ID, Client ID and Client Secret in your `.env` file.

## Usage

```php
$access_token = Auth::getToken();

$meeting = Meeting::setAccessToken($access_token)->create([
    'topic' => 'Test Meeting',
    'type' => 2,
    'start_time' => now()->addDay()->startOfHour()->format('Y-m-d\TH:i:s'),
    'duration' => 60,
], 'mail@example.com');
```

See the test cases for more usage examples.

## Testing

Update the phpunit.xml file with your Zoom API credentials.

```xml

<php>
    <env name="ZOOM_ACCOUNT_ID" value=""/>
    <env name="ZOOM_CLIENT_ID" value=""/>
    <env name="ZOOM_CLIENT_SECRET" value=""/>
    <env name="ZOOM_EMAIL_ACCOUNT" value=""/>
</php>
```

Run

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
