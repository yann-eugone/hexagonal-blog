<?php

namespace Acme\Domain\Blog\Exception\Post;

use Exception;
use RuntimeException;

class CannotUpdatePostException extends RuntimeException
{
    /**
     * @param Exception $exception
     *
     * @return CannotUpdatePostException
     */
    public static function onException(Exception $exception)
    {
        return new self(
            'Cannot update Post.',
            0,
            $exception
        );
    }
}
