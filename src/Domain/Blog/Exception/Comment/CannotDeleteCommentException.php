<?php

namespace Acme\Domain\Blog\Exception\Comment;

use Exception;
use RuntimeException;

class CannotDeleteCommentException extends RuntimeException
{
    /**
     * @param Exception $exception
     *
     * @return CannotDeleteCommentException
     */
    public static function onException(Exception $exception)
    {
        return new self(
            'Cannot delete Comment.',
            0,
            $exception
        );
    }
}
