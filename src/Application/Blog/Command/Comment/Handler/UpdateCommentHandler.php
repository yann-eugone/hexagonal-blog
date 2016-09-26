<?php

namespace Acme\Application\Blog\Command\Comment\Handler;

use Acme\Application\Blog\Command\Comment\UpdateComment;
use Acme\Application\Blog\Event\Comment\CommentUpdated;
use Acme\Application\Blog\Event\EventBus;
use Acme\Domain\Blog\Repository\CommentRepository;

class UpdateCommentHandler
{
    /**
     * @var CommentRepository
     */
    private $repository;

    /**
     * @var EventBus
     */
    private $eventBus;

    /**
     * @param CommentRepository $repository
     * @param EventBus          $eventBus
     */
    public function __construct(CommentRepository $repository, EventBus $eventBus)
    {
        $this->repository = $repository;
        $this->eventBus = $eventBus;
    }

    /**
     * @param UpdateComment $command
     */
    public function __invoke(UpdateComment $command)
    {
        $comment = $this->repository->getById($command->getId());

        $comment->setComment($command->getText());

        $this->repository->update($comment);

        $this->eventBus->dispatch(new CommentUpdated($comment->getId()));
    }
}
