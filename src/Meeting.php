<?php

namespace IClimber\LaravelZoomMeetings;

use IClimber\LaravelZoomMeetings\Exceptions\HttpException;
use IClimber\LaravelZoomMeetings\Support\Client;

class Meeting
{
    protected static string $accessToken;

    public static function setAccessToken(string $accessToken): Meeting
    {
        self::$accessToken = $accessToken;

        return new Meeting();
    }

    /**
     * @throws Exceptions\HttpException
     */
    public function create(array $data, ?string $userId = null): array
    {
        if (!$userId) {
            $userId = 'me';
        }

        return Client::post('users/' . urlencode($userId) . '/meetings', $data, self::$accessToken);
    }

    /**
     * @throws Exceptions\HttpException
     */
    public function delete(int $id): array
    {
        return Client::delete('meetings/' . $id, self::$accessToken);
    }

    /**
     * @throws HttpException
     */
    public function findBy(string $field, string $value, string $userId = null): array
    {
        if (!$userId) {
            $userId = 'me';
        }

        $meetings = Client::get('users/' . urlencode($userId) . '/meetings', self::$accessToken);

        foreach ($meetings['body']['meetings'] as $meeting) {
            if ($meeting[$field] === $value) {

                return $meeting;
            }
        }

        return [];
    }
}
