<?php

namespace Acme\Domain\Blog\Exception\Comment;

use Exception;
use RuntimeException;

class CannotCreateCommentException extends RuntimeException
{
    /**
     * @param Exception $exception
     *
     * @return CannotCreateCommentException
     */
    public static function onException(Exception $exception)
    {
        return new self(
            'Cannot create Comment.',
            0,
            $exception
        );
    }
}
