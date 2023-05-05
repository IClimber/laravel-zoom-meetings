<?php

namespace IClimber\LaravelZoomMeetings\Support;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use IClimber\LaravelZoomMeetings\Exceptions\MissingConfigException;

class Auth
{
    /**
     * @throws GuzzleException
     * @throws MissingConfigException
     */
    public static function getToken(): string
    {
        $accountId = config('zoom-meetings.account_id');
        $clientId = config('zoom-meetings.client_id');
        $clientSecret = config('zoom-meetings.client_secret');

        if (!$accountId || !$clientId || !$clientSecret) {
            throw new MissingConfigException('Zoom account_id, client_id and client_secret must be set in config/zoom.php');
        }

        $client = new Client([
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($clientId . ':' . $clientSecret),
                'Host' => 'zoom.us',
            ],
        ]);

        $response = $client->request('POST', config('zoom-meetings.token_url'), [
            'form_params' => [
                'grant_type' => 'account_credentials',
                'account_id' => $accountId,
            ],
        ]);

        $responseBody = json_decode($response->getBody(), true);

        return $responseBody['access_token'];
    }

    /**
     * @param string $refreshToken
     * @return array
     * @throws GuzzleException
     * @throws MissingConfigException
     */
    public static function refreshToken(string $refreshToken): array
    {
        $clientId = config('zoom-meetings.client_id');
        $clientSecret = config('zoom-meetings.client_secret');

        if (!$clientId || !$clientSecret) {
            throw new MissingConfigException('Zoom client_id and client_secret must be set in config/zoom.php');
        }

        $options = [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($clientId . ':' . $clientSecret),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ];

        $params = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken
        ];

        $response = (new Client($options))->post(config('zoom-meetings.token_url'), [
            'form_params' => $params
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * @param string $accessToken
     * @return bool
     * @throws GuzzleException
     * @throws MissingConfigException
     */
    public static function revokeToken(string $accessToken): bool
    {
        $clientId = config('zoom-meetings.client_id');
        $clientSecret = config('zoom-meetings.client_secret');

        if (!$clientId || !$clientSecret) {
            throw new MissingConfigException('Zoom client_id and client_secret must be set in config/zoom.php');
        }

        $options = [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($clientId . ':' . $clientSecret),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ];

        $params = [
            'token' => $accessToken,
        ];

        $response = (new Client($options))->post(config('zoom-meetings.revoke_url'), [
            'form_params' => $params
        ]);

        $responseBody = json_decode($response->getBody(), true);

        return ($responseBody['status'] ?? '') == 'success';
    }
}
