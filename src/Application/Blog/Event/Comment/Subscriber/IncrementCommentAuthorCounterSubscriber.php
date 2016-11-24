<?php

namespace Acme\Application\Blog\Event\Comment\Subscriber;

use Acme\Application\Blog\Event\Comment\CommentCreated;
use Acme\Application\Blog\Event\Comment\CommentDeleted;
use Acme\Application\Blog\Event\Exception\UnexpectedEventException;
use Acme\Domain\Blog\Repository\CommentAuthorCounterRepository;
use Acme\Domain\Blog\Repository\CommentRepository;
use DateTime;

class IncrementCommentAuthorCounterSubscriber
{
    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * @var CommentAuthorCounterRepository
     */
    private $counterRepository;

    /**
     * @param CommentRepository              $commentRepository
     * @param CommentAuthorCounterRepository $counterRepository
     */
    public function __construct(CommentRepository $commentRepository, CommentAuthorCounterRepository $counterRepository)
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
            throw UnexpectedEventException::create($this, $event);
        }

        $comment = $this->commentRepository->getById($event->getId());

        $increment = $incrementMap[get_class($event)];

        $this->counterRepository->incrementCount($comment->getAuthor(), $increment);
        $this->counterRepository->incrementCountThatDay($comment->getAuthor(), new DateTime(), $increment);
    }
}
