<?php

namespace Acme\Application\Blog\Event\Comment\Subscriber;

use Acme\Application\Blog\Event\Comment\CommentCreated;
use Acme\Application\Blog\Event\Comment\CommentDeleted;
use Acme\Domain\Blog\Repository\CommentCounterRepository;
use Acme\Domain\Blog\Repository\CommentRepository;
use DateTime;

class IncrementCounterSubscriber
{
    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * @var CommentCounterRepository
     */
    private $counterRepository;

    /**
     * @param CommentRepository        $commentRepository
     * @param CommentCounterRepository $counterRepository
     */
    public function __construct(CommentRepository $commentRepository, CommentCounterRepository $counterRepository)
    {
        $this->commentRepository = $commentRepository;
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
            return; //todo at this point we should probably report something
        }

        $comment = $this->commentRepository->getById($event->getId());

        $increment = $incrementMap[get_class($event)];

        $this->counterRepository->incrementCount($increment);
        $this->counterRepository->incrementCountThatDay(new DateTime(), $increment);
        $this->counterRepository->incrementCountForAuthor($comment->getAuthor(), $increment);
        $this->counterRepository->incrementCountForAuthorThatDay($comment->getAuthor(), new DateTime(), $increment);
    }
}