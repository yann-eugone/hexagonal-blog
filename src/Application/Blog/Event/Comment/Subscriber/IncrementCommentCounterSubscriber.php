<?php

namespace Acme\Application\Blog\Event\Comment\Subscriber;

use Acme\Application\Blog\Event\Comment\CommentCreated;
use Acme\Application\Blog\Event\Comment\CommentDeleted;
use Acme\Domain\Blog\Repository\CommentCounterRepository;
use DateTime;

class IncrementCommentCounterSubscriber
{
    /**
     * @var CommentCounterRepository
     */
    private $counterRepository;

    /**
     * @param CommentCounterRepository $counterRepository
     */
    public function __construct(CommentCounterRepository $counterRepository)
    {
        $this->counterRepository = $counterRepository;
    }

    /**
     * @param CommentCreated $event
     */
    public function created(CommentCreated $event)
    {
        $date = new DateTime($event->getData()['posted_at']);

        $this->counterRepository->incrementCount(1);
        $this->counterRepository->incrementCountThatDay($date, 1);
    }

    /**
     * @param CommentDeleted $event
     */
    public function deleted(CommentDeleted $event)
    {
        $date = new DateTime($event->getData()['posted_at']);

        $this->counterRepository->incrementCount(-1);
        $this->counterRepository->incrementCountThatDay($date, -1);
    }
}
