<?php

namespace Acme\Domain\Blog\Exception\Comment;

use Exception;
use RuntimeException;

class CannotUpdateCommentException extends RuntimeException
{
    /**
     * @param Exception $exception
     *
     * @return CannotUpdateCommentException
     */
    public static function onException(Exception $exception)
    {
        return new self(
            'Cannot update Comment.',
            0,
            $exception
        );
    }
}
