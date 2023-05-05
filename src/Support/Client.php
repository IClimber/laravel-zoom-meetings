<?php

namespace IClimber\LaravelZoomMeetings\Support;

use IClimber\LaravelZoomMeetings\Exceptions\HttpException;
use Illuminate\Support\Facades\Http;

class Client
{
    private static function requestHeaders(string $accessToken): array
    {
        return [
            'authorization' => 'bearer ' . $accessToken,
            'accept' => 'application/json',
        ];
    }

    /**
     * @throws httpexception
     */
    public static function get(string $uri, string $accessToken): array
    {
        $response = http::withHeaders(self::requestHeaders($accessToken))
            ->get(config('zoom-meetings.base_url') . $uri);

        return self::handleResponse($response, $uri);
    }

    /**
     * @throws httpexception
     */
    public static function post(string $uri, array $data, string $accessToken): array
    {
        $response = Http::withHeaders(self::requestHeaders($accessToken))
            ->post(config('zoom-meetings.base_url') . $uri, $data);

        return self::handleResponse($response, $uri);
    }

    /**
     * @throws HttpException
     */
    public static function delete(string $uri, string $accessToken): array
    {
        $response = Http::withHeaders(self::requestHeaders($accessToken))
            ->delete(config('zoom-meetings.base_url') . $uri);

        return self::handleResponse($response, $uri);
    }

    /**
     * @throws HttpException
     */
    private static function handleResponse($response, $uri): array
    {
        if ($response->failed()) {
            $message = $response->json('error_message', 'unknown error');
            throw HttpException::new($uri, $response->status(), $message, $response->json());
        }

        return [
            'status' => $response->status(),
            'body' => json_decode($response->getbody(), true),
        ];
    }
}
