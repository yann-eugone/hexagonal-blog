<?php

namespace Acme\Application\Blog\Event\Post\Subscriber;

use Acme\Application\Blog\Event\Post\PostCreated;
use Acme\Application\Blog\Event\Post\PostDeleted;
use Acme\Domain\Blog\Repository\PostCounterRepository;
use DateTime;

class IncrementPostCounterSubscriber
{
    /**
     * @var PostCounterRepository
     */
    private $counterRepository;

    /**
     * @param PostCounterRepository $counterRepository
     */
    public function __construct(PostCounterRepository $counterRepository)
    {
        $this->counterRepository = $counterRepository;
    }

    /**
     * @param PostCreated $event
     */
    public function created(PostCreated $event)
    {
        $date = new DateTime($event->getData()['posted_at']);

        $this->counterRepository->incrementCount(1);
        $this->counterRepository->incrementCountThatDay($date, 1);
    }

    /**
     * @param PostDeleted $event
     */
    public function deleted(PostDeleted $event)
    {
        $date = new DateTime($event->getData()['posted_at']);

        $this->counterRepository->incrementCount(-1);
        $this->counterRepository->incrementCountThatDay($date, -1);
    }
}
