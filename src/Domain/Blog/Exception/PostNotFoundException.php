<?php

namespace Acme\Domain\Blog\Exception;

use RuntimeException;

class PostNotFoundException extends RuntimeException
{
    /**
     * @param int $id
     *
     * @return PostNotFoundException
     */
    public static function byId($id)
    {
        return new self(
            sprintf('Post with id %d was not found.', $id)
        );
    }
}
