<?php

namespace Acme\Application\Blog\Command\Comment\Handler;

use Acme\Application\Blog\Command\Comment\CreateComment;
use Acme\Application\Blog\Event\Comment\CommentCreated;
use Acme\Application\Blog\Event\EventBus;
use Acme\Domain\Blog\Repository\CommentRepository;
use DateTime;

class CreateCommentHandler
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
     * @param CreateComment $command
     */
    public function __invoke(CreateComment $command)
    {
        $comment = $this->repository->instance();

        $command->setComment($comment);

        $comment->setComment($command->getText());
        $comment->setPost($command->getPost());
        $comment->setAuthor($command->getAuthor());
        $comment->setPostedAt(new DateTime());

        $this->repository->create($comment);

        $this->eventBus->dispatch(new CommentCreated($comment->getId()));
    }
}
