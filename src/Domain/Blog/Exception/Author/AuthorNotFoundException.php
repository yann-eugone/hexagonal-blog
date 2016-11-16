<?php

namespace Acme\Domain\Blog\Exception\Author;

use RuntimeException;

class AuthorNotFoundException extends RuntimeException
{
    /**
     * @param int $id
     *
     * @return AuthorNotFoundException
     */
    public static function byId($id)
    {
        return new self(
            sprintf('Author with id %d was not found.', $id)
        );
    }
}
