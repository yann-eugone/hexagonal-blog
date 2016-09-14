<?php

namespace Acme\Domain\Blog\Exception;

use Exception;
use RuntimeException;

class CannotDeletePostException extends RuntimeException
{
    /**
     * @param Exception $exception
     *
     * @return CannotDeletePostException
     */
    public static function onException(Exception $exception)
    {
        return new self(
            'Cannot delete Post.',
            0,
            $exception
        );
    }
}
