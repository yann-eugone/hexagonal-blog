<?php

namespace Acme\Application\Blog\Command\Comment\Handler;

use Acme\Application\Blog\Command\Comment\UpdateComment;
use Acme\Application\Blog\Event\Comment\CommentEventFactory;
use Acme\Application\Common\Event\EventBus;
use Acme\Domain\Blog\Repository\CommentRepository;

class UpdateCommentHandler
{
    /**
     * @var CommentRepository
     */
    private $repository;

    /**
     * @var CommentEventFactory
     */
    private $eventFactory;

    /**
     * @var EventBus
     */
    private $eventBus;

    /**
     * @param CommentRepository   $repository
     * @param CommentEventFactory $eventFactory
     * @param EventBus            $eventBus
     */
    public function __construct(CommentRepository $repository, CommentEventFactory $eventFactory, EventBus $eventBus)
    {
        $this->repository = $repository;
        $this->eventFactory = $eventFactory;
        $this->eventBus = $eventBus;
    }

    /**
     * @param UpdateComment $command
     */
    public function __invoke(UpdateComment $command)
    {
        $comment = $this->repository->getById($command->getId());

        $referenceComment = clone $comment;

        $comment->setComment($command->getText());

        $this->repository->update($comment);

        $this->eventBus->dispatch($this->eventFactory->newUpdatedEvent($referenceComment, $comment));
    }
}
