<?php

namespace IClimber\LaravelZoomMeetings\Exceptions;

use Exception;

class MissingConfigException extends Exception
{
    public static function serviceRespondedWithAnError(Exception $exception): self
    {
        return new static($exception->getMessage());
    }
}
