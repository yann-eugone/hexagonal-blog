<?php

namespace Acme\Application\Blog\Event\Post\Subscriber;

use Acme\Application\Blog\Event\Post\PostCreated;
use Acme\Application\Blog\Event\Post\PostDeleted;
use Acme\Domain\Blog\Repository\AuthorRepository;
use Acme\Domain\Blog\Repository\PostAuthorCounterRepository;
use DateTime;

class IncrementPostAuthorCounterSubscriber
{
    /**
     * @var AuthorRepository
     */
    private $authorRepository;

    /**
     * @var PostAuthorCounterRepository
     */
    private $counterRepository;

    /**
     * @param AuthorRepository            $authorRepository
     * @param PostAuthorCounterRepository $counterRepository
     */
    public function __construct(AuthorRepository $authorRepository, PostAuthorCounterRepository $counterRepository)
    {
        $this->authorRepository = $authorRepository;
        $this->counterRepository = $counterRepository;
    }

    /**
     * @param PostCreated $event
     */
    public function created(PostCreated $event)
    {
        $author = $this->authorRepository->getById($event->getData()['author_id']);
        $date = new DateTime($event->getData()['posted_at']);

        $this->counterRepository->incrementCount($author, 1);
        $this->counterRepository->incrementCountThatDay($author, $date, 1);
    }

    /**
     * @param PostDeleted $event
     */
    public function deleted(PostDeleted $event)
    {
        $author = $this->authorRepository->getById($event->getData()['author_id']);
        $date = new DateTime($event->getData()['posted_at']);

        $this->counterRepository->incrementCount($author, -1);
        $this->counterRepository->incrementCountThatDay($author, $date, -1);
    }
}
