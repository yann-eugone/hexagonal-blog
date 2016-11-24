<?php

namespace Acme\Application\Blog\Event\Post\Subscriber;

use Acme\Application\Blog\Event\Exception\UnexpectedEventException;
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
     * @param PostCreated|PostDeleted $event
     */
    public function __invoke($event)
    {
        $incrementMap = [
            PostCreated::class => 1,
            PostDeleted::class => -1,
        ];

        if (!isset($incrementMap[get_class($event)])) {
            throw UnexpectedEventException::create($this, $event);
        }

        $increment = $incrementMap[get_class($event)];

        $this->counterRepository->incrementCount($increment);
        $this->counterRepository->incrementCountThatDay(new DateTime(), $increment);
    }
}
