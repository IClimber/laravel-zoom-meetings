<?php

namespace IClimber\LaravelZoomMeetings;

use IClimber\LaravelZoomMeetings\Exceptions\HttpException;
use IClimber\LaravelZoomMeetings\Exceptions\InvalidAccessTokenException;
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
     * @param array $data
     * @param string|null $userId
     * @return array
     * @throws HttpException
     * @throws InvalidAccessTokenException
     */
    public function create(array $data, ?string $userId = null): array
    {
        if (!$userId) {
            $userId = 'me';
        }

        return Client::post('users/' . urlencode($userId) . '/meetings', $data, self::$accessToken);
    }

    /**
     * @param int $id
     * @return array
     * @throws HttpException
     * @throws InvalidAccessTokenException
     */
    public function delete(int $id): array
    {
        return Client::delete('meetings/' . $id, self::$accessToken);
    }

    /**
     * @param string $field
     * @param string $value
     * @param string|null $userId
     * @return array
     * @throws HttpException
     * @throws InvalidAccessTokenException
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
