<?php

namespace Acme\Application\Blog\Event\Comment\Subscriber;

use Acme\Application\Blog\Event\Comment\CommentCreated;
use Acme\Application\Blog\Event\Comment\CommentDeleted;
use Acme\Application\Blog\Event\Exception\UnexpectedEventException;
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
     * @param CommentCreated|CommentDeleted $event
     */
    public function __invoke($event)
    {
        $incrementMap = [
            CommentCreated::class => 1,
            CommentDeleted::class => -1,
        ];

        if (!isset($incrementMap[get_class($event)])) {
            throw UnexpectedEventException::create($this, $event);
        }

        $increment = $incrementMap[get_class($event)];

        $this->counterRepository->incrementCount($increment);
        $this->counterRepository->incrementCountThatDay(new DateTime(), $increment);
    }
}
