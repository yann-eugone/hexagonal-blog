<?php

namespace Acme\Application\Blog\Event\Author\Subscriber;

use Acme\Application\Blog\Event\Comment\CommentCreated;
use Acme\Application\Blog\Event\Comment\CommentDeleted;
use Acme\Application\Blog\Event\Post\PostCreated;
use Acme\Application\Blog\Event\Post\PostDeleted;
use Acme\Application\Blog\Event\Post\PostFavorited;
use Acme\Application\Blog\Event\Post\PostUnfavorited;
use Acme\Domain\Blog\Repository\AuthorRepository;
use Acme\Domain\Blog\Repository\AuthorCommentCounterRepository;
use Acme\Domain\Blog\Repository\AuthorFavoriteCounterRepository;
use Acme\Domain\Blog\Repository\AuthorPostCounterRepository;
use DateTime;

class IncrementCounterSubscriber
{
    /**
     * @var AuthorRepository
     */
    private $authorRepository;

    /**
     * @var AuthorPostCounterRepository
     */
    private $postCounterRepository;

    /**
     * @var AuthorFavoriteCounterRepository
     */
    private $favoriteCounterRepository;

    /**
     * @var AuthorCommentCounterRepository
     */
    private $commentCounterRepository;

    /**
     * @param AuthorRepository                $authorRepository
     * @param AuthorPostCounterRepository     $postCounterRepository
     * @param AuthorFavoriteCounterRepository $favoriteCounterRepository
     * @param AuthorCommentCounterRepository  $commentCounterRepository
     */
    public function __construct(
        AuthorRepository $authorRepository,
        AuthorPostCounterRepository $postCounterRepository,
        AuthorFavoriteCounterRepository $favoriteCounterRepository,
        AuthorCommentCounterRepository $commentCounterRepository
    ) {
        $this->authorRepository = $authorRepository;
        $this->postCounterRepository = $postCounterRepository;
        $this->favoriteCounterRepository = $favoriteCounterRepository;
        $this->commentCounterRepository = $commentCounterRepository;
    }

    /**
     * @param PostCreated $event
     */
    public function postCreated(PostCreated $event)
    {
        $author = $this->authorRepository->getById($event->getData()['author_id']);
        $date = new DateTime($event->getData()['posted_at']);

        $this->postCounterRepository->incrementCount($author, 1);
        $this->postCounterRepository->incrementCountThatDay($author, $date, 1);
    }

    /**
     * @param PostDeleted $event
     */
    public function postDeleted(PostDeleted $event)
    {
        $author = $this->authorRepository->getById($event->getData()['author_id']);
        $date = new DateTime($event->getData()['posted_at']);

        $this->postCounterRepository->incrementCount($author, -1);
        $this->postCounterRepository->incrementCountThatDay($author, $date, -1);
    }

    /**
     * @param PostFavorited $event
     */
    public function postFavorited(PostFavorited $event)
    {
        $author = $this->authorRepository->getById($event->getAuthorId());
        $date = $event->getDate();

        $this->favoriteCounterRepository->incrementCount($author, 1);
        $this->favoriteCounterRepository->incrementCountThatDay($author, $date, 1);
    }

    /**
     * @param PostUnfavorited $event
     */
    public function postUnfavorited(PostUnfavorited $event)
    {
        $author = $this->authorRepository->getById($event->getAuthorId());
        $date = $event->getDate();

        $this->favoriteCounterRepository->incrementCount($author, -1);
        $this->favoriteCounterRepository->incrementCountThatDay($author, $date, -1);
    }

    /**
     * @param CommentCreated $event
     */
    public function commentCreated(CommentCreated $event)
    {
        $author = $this->authorRepository->getById($event->getData()['author_id']);
        $date = new DateTime($event->getData()['posted_at']);

        $this->commentCounterRepository->incrementCount($author, 1);
        $this->commentCounterRepository->incrementCountThatDay($author, $date, 1);
    }

    /**
     * @param CommentDeleted $event
     */
    public function commentDeleted(CommentDeleted $event)
    {
        $author = $this->authorRepository->getById($event->getData()['author_id']);
        $date = new DateTime($event->getData()['posted_at']);

        $this->commentCounterRepository->incrementCount($author, -1);
        $this->commentCounterRepository->incrementCountThatDay($author, $date, -1);
    }
}
