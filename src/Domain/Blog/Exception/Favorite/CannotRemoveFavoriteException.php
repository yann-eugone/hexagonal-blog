<?php

namespace Acme\Domain\Blog\Exception\Favorite;

use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Model\Post;
use Exception;
use RuntimeException;

class CannotRemoveFavoriteException extends RuntimeException
{
    /**
     * @param Author $author
     * @param Post   $post
     *
     * @return CannotRemoveFavoriteException
     */
    public static function notFavorited(Author $author, Post $post)
    {
        return new self(
            sprintf(
                'Author "%s" never added the post "%s" to favorite.',
                $author,
                $post
            )
        );
    }

    /**
     * @param Exception $exception
     *
     * @return CannotRemoveFavoriteException
     */
    public static function onException(Exception $exception)
    {
        return new self(
            'Cannot remove Favorite.',
            0,
            $exception
        );
    }
}
