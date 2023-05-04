<?php

namespace IClimber\LaravelZoomMeetings\Facades;

use Illuminate\Support\Facades\Facade;
use IClimber\LaravelZoomMeetings\Meeting;

/**
 * @see \IClimber\LaravelZoomMeetings\Facades\Meeting
 */
class LaravelZoomMeetings extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Meeting::class;
    }
}
