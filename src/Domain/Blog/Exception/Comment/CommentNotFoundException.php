<?php

namespace Acme\Domain\Blog\Exception\Comment;

use RuntimeException;

class CommentNotFoundException extends RuntimeException
{
    /**
     * @param int $id
     *
     * @return CommentNotFoundException
     */
    public static function byId($id)
    {
        return new self(
            sprintf('Comment with id %d was not found.', $id)
        );
    }
}
