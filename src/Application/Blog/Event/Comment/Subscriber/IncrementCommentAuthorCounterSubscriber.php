<?php

namespace Acme\Application\Blog\Event\Comment\Subscriber;

use Acme\Application\Blog\Event\Comment\CommentCreated;
use Acme\Application\Blog\Event\Comment\CommentDeleted;
use Acme\Domain\Blog\Repository\AuthorRepository;
use Acme\Domain\Blog\Repository\CommentAuthorCounterRepository;
use DateTime;

class IncrementCommentAuthorCounterSubscriber
{
    /**
     * @var AuthorRepository
     */
    private $authorRepository;

    /**
     * @var CommentAuthorCounterRepository
     */
    private $counterRepository;

    /**
     * @param AuthorRepository               $authorRepository
     * @param CommentAuthorCounterRepository $counterRepository
     */
    public function __construct(AuthorRepository $authorRepository, CommentAuthorCounterRepository $counterRepository)
    {
        $this->authorRepository = $authorRepository;
        $this->counterRepository = $counterRepository;
    }

    /**
     * @param CommentCreated $event
     */
    public function created(CommentCreated $event)
    {
        $author = $this->authorRepository->getById($event->getData()['author_id']);
        $date = new DateTime($event->getData()['posted_at']);

        $this->counterRepository->incrementCount($author, 1);
        $this->counterRepository->incrementCountThatDay($author, $date, 1);
    }

    /**
     * @param CommentDeleted $event
     */
    public function deleted(CommentDeleted $event)
    {
        $author = $this->authorRepository->getById($event->getData()['author_id']);
        $date = new DateTime($event->getData()['posted_at']);

        $this->counterRepository->incrementCount($author, -1);
        $this->counterRepository->incrementCountThatDay($author, $date, -1);
    }
}
