<?php

namespace Acme\Domain\Blog\Repository;

use Acme\Domain\Blog\Exception\Favorite\CannotAddFavoriteException;
use Acme\Domain\Blog\Exception\Favorite\CannotRemoveFavoriteException;
use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Model\Post;

interface FavoriteRepository
{
    /**
     * @param Post $post
     *
     * @return Author[]
     */
    public function listForPost(Post $post);

    /**
     * @param Author $author
     *
     * @return Post[]
     */
    public function listForAuthor(Author $author);

    /**
     * @param Post   $post
     * @param Author $author
     *
     * @throws CannotAddFavoriteException
     */
    public function add(Post $post, Author $author);

    /**
     * @param Post   $post
     * @param Author $author
     *
     * @throws CannotRemoveFavoriteException
     */
    public function remove(Post $post, Author $author);
}
