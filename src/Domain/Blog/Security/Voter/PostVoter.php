<?php

namespace Acme\Domain\Blog\Security\Voter;

use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Model\Post;
use Acme\Domain\Blog\Repository\FavoriteRepository;

class PostVoter
{
    /**
     * @var FavoriteRepository
     */
    private $favoriteRepository;

    /**
     * @param FavoriteRepository $favoriteRepository
     */
    public function __construct(FavoriteRepository $favoriteRepository)
    {
        $this->favoriteRepository = $favoriteRepository;
    }

    /**
     * @param Author|null $author
     *
     * @return bool
     */
    public function allowedToCreate(Author $author = null)
    {
        return null !== $author;
    }

    /**
     * @param Post        $post
     * @param Author|null $author
     *
     * @return bool
     */
    public function allowedToUpdate(Post $post, Author $author = null)
    {
        return null !== $author
               && $author === $post->getAuthor();
    }

    /**
     * @param Post        $post
     * @param Author|null $author
     *
     * @return bool
     */
    public function allowedToDelete(Post $post, Author $author = null)
    {
        return null !== $author
               && $author === $post->getAuthor();
    }

    /**
     * @param Post        $post
     * @param Author|null $author
     *
     * @return bool
     */
    public function allowedToComment(Post $post, Author $author = null)
    {
        return null !== $author;
    }

    /**
     * @param Post        $post
     * @param Author|null $author
     *
     * @return bool
     */
    public function allowedToFavorite(Post $post, Author $author = null)
    {
        return null !== $author
               && !$this->favoriteRepository->has($post, $author);
    }

    /**
     * @param Post        $post
     * @param Author|null $author
     *
     * @return bool
     */
    public function allowedToUnfavorite(Post $post, Author $author = null)
    {
        return null !== $author
               && $this->favoriteRepository->has($post, $author);
    }
}
