<?php

namespace Acme\Domain\Blog\Exception\Tag;

use RuntimeException;

class TagNotFoundException extends RuntimeException
{
    /**
     * @param int $id
     *
     * @return TagNotFoundException
     */
    public static function byId($id)
    {
        return new self(
            sprintf('Tag with id %d was not found.', $id)
        );
    }
}
