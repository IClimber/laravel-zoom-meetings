<?php

namespace IClimber\LaravelZoomMeetings\Support;

use IClimber\LaravelZoomMeetings\Exceptions\HttpException;
use IClimber\LaravelZoomMeetings\Exceptions\InvalidAccessTokenException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

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
     * @param string $uri
     * @param string $accessToken
     * @return array
     * @throws HttpException
     * @throws InvalidAccessTokenException
     */
    public static function get(string $uri, string $accessToken): array
    {
        $response = http::withHeaders(self::requestHeaders($accessToken))
            ->get(config('zoom-meetings.base_url') . $uri);

        return self::handleResponse($response, $uri);
    }

    /**
     * @param string $uri
     * @param array $data
     * @param string $accessToken
     * @return array
     * @throws HttpException
     * @throws InvalidAccessTokenException
     */
    public static function post(string $uri, array $data, string $accessToken): array
    {
        $response = Http::withHeaders(self::requestHeaders($accessToken))
            ->post(config('zoom-meetings.base_url') . $uri, $data);

        return self::handleResponse($response, $uri);
    }

    /**
     * @param string $uri
     * @param array $data
     * @param string $accessToken
     * @return array
     * @throws HttpException
     * @throws InvalidAccessTokenException
     */
    public static function delete(string $uri, array $data, string $accessToken): array
    {
        $response = Http::withHeaders(self::requestHeaders($accessToken))
            ->delete(config('zoom-meetings.base_url') . $uri, $data);

        return self::handleResponse($response, $uri);
    }

    /**
     * @param Response $response
     * @param $uri
     * @return array
     * @throws HttpException
     * @throws InvalidAccessTokenException
     */
    private static function handleResponse(Response $response, $uri): array
    {
        if ($response->failed()) {
            if ($response->status() == BaseResponse::HTTP_UNAUTHORIZED) {
                throw new InvalidAccessTokenException($response->body());
            }

            $message = $response->json('error_message', 'unknown error');
            throw HttpException::new($uri, $response->status(), $message, $response->json());
        }

        return [
            'status' => $response->status(),
            'body' => json_decode($response->body(), true),
        ];
    }
}
