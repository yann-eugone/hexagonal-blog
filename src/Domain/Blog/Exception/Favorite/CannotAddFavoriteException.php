<?php

namespace Acme\Domain\Blog\Exception\Favorite;

use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Model\Post;
use Exception;
use RuntimeException;

class CannotAddFavoriteException extends RuntimeException
{
    /**
     * @param Author $author
     * @param Post $post
     *
     * @return CannotAddFavoriteException
     */
    public static function alreadyFavorited(Author $author, Post $post)
    {
        return new self(
            sprintf(
                'Author "%s" already added the post "%s" as favorite.',
                $author,
                $post
            )
        );
    }

    /**
     * @param Exception $exception
     *
     * @return CannotAddFavoriteException
     */
    public static function onException(Exception $exception)
    {
        return new self(
            'Cannot add Favorite.',
            0,
            $exception
        );
    }
}
