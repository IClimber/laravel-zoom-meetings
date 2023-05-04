<?php

namespace IClimber\LaravelZoomMeetings;

use IClimber\LaravelZoomMeetings\Exceptions\HttpException;
use IClimber\LaravelZoomMeetings\Support\Client;

class User
{
    protected static string $access_token;

    public static function setAccessToken(string $access_token): User
    {
        self::$access_token = $access_token;

        return new User();
    }

    /**
     * @throws HttpException
     */
    public static function all(): array
    {
        return Client::get('users', self::$access_token);
    }

    /**
     * @throws HttpException
     */
    public static function find(string $email): array
    {
        return Client::get('users/' . urlencode($email), self::$access_token);
    }

    /**
     * @throws HttpException
     */
    public function me(): array
    {
        return Client::get('users/me', self::$access_token);
    }
}
