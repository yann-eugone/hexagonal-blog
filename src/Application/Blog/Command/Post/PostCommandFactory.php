<?php

namespace Acme\Application\Blog\Command\Post;

use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Model\Post;
use DateTime;

class PostCommandFactory
{
    /**
     * @param Author $author
     *
     * @return CreatePost
     */
    public function newCreateCommand(Author $author)
    {
        return new CreatePost($author);
    }

    /**
     * @param Post $post
     *
     * @return UpdatePost
     */
    public function newUpdateCommand(Post $post)
    {
        return new UpdatePost(
            $post->getId(),
            $post->getTitle(),
            $post->getSummary(),
            $post->getBody(),
            $post->getCategory(),
            $post->getTags()
        );
    }

    /**
     * @param Post $post
     *
     * @return DeletePost
     */
    public function newDeleteCommand(Post $post)
    {
        return new DeletePost($post->getId());
    }

    /**
     * @param Post     $post
     * @param Author   $author
     * @param DateTime $date
     *
     * @return FavoritePost
     */
    public function newFavoriteCommand(Post $post, Author $author, DateTime $date = null)
    {
        return new FavoritePost(
            $post->getId(),
            $author->getId(),
            $date ?: new DateTime()
        );
    }

    /**
     * @param Post     $post
     * @param Author   $author
     * @param DateTime $date
     *
     * @return UnfavoritePost
     */
    public function newUnfavoriteCommand(Post $post, Author $author, DateTime $date = null)
    {
        return new UnfavoritePost(
            $post->getId(),
            $author->getId(),
            $date ?: new DateTime()
        );
    }
}
