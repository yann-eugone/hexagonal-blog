<?php

namespace Acme\Domain\Blog\Exception\Post;

use Exception;
use RuntimeException;

class CannotCreatePostException extends RuntimeException
{
    /**
     * @param Exception $exception
     *
     * @return CannotCreatePostException
     */
    public static function onException(Exception $exception)
    {
        return new self(
            'Cannot create Post.',
            0,
            $exception
        );
    }
}
