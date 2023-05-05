<?php

namespace IClimber\LaravelZoomMeetings;

use IClimber\LaravelZoomMeetings\Exceptions\HttpException;
use IClimber\LaravelZoomMeetings\Exceptions\InvalidAccessTokenException;
use IClimber\LaravelZoomMeetings\Support\Client;

class User
{
    protected static string $accessToken;

    public static function setAccessToken(string $accessToken): User
    {
        self::$accessToken = $accessToken;

        return new User();
    }

    /**
     * @return array
     * @throws HttpException
     * @throws InvalidAccessTokenException
     */
    public static function all(): array
    {
        return Client::get('users', self::$accessToken);
    }

    /**
     * @param string $email
     * @return array
     * @throws HttpException
     * @throws InvalidAccessTokenException
     */
    public static function find(string $email): array
    {
        return Client::get('users/' . urlencode($email), self::$accessToken);
    }

    /**
     * @return array
     * @throws HttpException
     * @throws InvalidAccessTokenException
     */
    public function me(): array
    {
        return Client::get('users/me', self::$accessToken);
    }
}
