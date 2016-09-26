<?php

namespace Acme\Application\Blog\Command\Comment\Handler;

use Acme\Application\Blog\Command\Comment\DeleteComment;
use Acme\Application\Blog\Event\Comment\CommentEventFactory;
use Acme\Application\Blog\Event\EventBus;
use Acme\Domain\Blog\Repository\CommentRepository;

class DeleteCommentHandler
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
     * @param DeleteComment $command
     */
    public function __invoke(DeleteComment $command)
    {
        $comment = $this->repository->getById($command->getId());

        $event = $this->eventFactory->newDeletedEvent($comment);

        $this->repository->delete($comment);

        $this->eventBus->dispatch($event);
    }
}
