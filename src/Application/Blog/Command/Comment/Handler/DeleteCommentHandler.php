<?php

namespace Acme\Application\Blog\Command\Comment\Handler;

use Acme\Application\Blog\Command\Comment\DeleteComment;
use Acme\Application\Blog\Event\Comment\CommentDeleted;
use Acme\Application\Blog\Event\EventBus;
use Acme\Domain\Blog\Repository\CommentRepository;
use Acme\Domain\Blog\Repository\PostRepository;

class DeleteCommentHandler
{
    /**
     * @var PostRepository
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
     * @param DeleteComment $command
     */
    public function __invoke(DeleteComment $command)
    {
        $comment = $this->repository->getById($command->getId());

        $this->repository->delete($comment);

        $this->eventBus->dispatch(new CommentDeleted($command->getId()));
    }
}
