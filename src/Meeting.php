<?php

namespace IClimber\LaravelZoomMeetings;

use IClimber\LaravelZoomMeetings\Exceptions\HttpException;
use IClimber\LaravelZoomMeetings\Support\Client;

class Meeting
{
    protected static string $access_token;

    public static function setAccessToken(string $access_token): Meeting
    {
        self::$access_token = $access_token;

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

        return Client::post('users/' . urlencode($userId) . '/meetings', $data, self::$access_token);
    }

    /**
     * @throws Exceptions\HttpException
     */
    public function delete(int $id): array
    {
        return Client::delete('meetings/' . $id, self::$access_token);
    }

    /**
     * @throws HttpException
     */
    public function findBy(string $field, string $value, string $userId = null): array
    {
        if (!$userId) {
            $userId = 'me';
        }

        $meetings = Client::get('users/' . urlencode($userId) . '/meetings', self::$access_token);

        foreach ($meetings['body']['meetings'] as $meeting) {
            if ($meeting[$field] === $value) {

                return $meeting;
            }
        }

        return [];
    }
}
