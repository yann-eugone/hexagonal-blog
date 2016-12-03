<?php

namespace Acme\Application\Blog\Command\Comment\Handler;

use Acme\Application\Blog\Command\Comment\CreateComment;
use Acme\Application\Blog\Event\Comment\CommentEventFactory;
use Acme\Application\Common\Event\EventBus;
use Acme\Domain\Blog\Repository\CommentRepository;
use DateTime;

class CreateCommentHandler
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
     * @var \Acme\Application\Common\Event\EventBus
     */
    private $eventBus;

    /**
     * @param CommentRepository                       $repository
     * @param CommentEventFactory                     $eventFactory
     * @param \Acme\Application\Common\Event\EventBus $eventBus
     */
    public function __construct(CommentRepository $repository, CommentEventFactory $eventFactory, EventBus $eventBus)
    {
        $this->repository = $repository;
        $this->eventFactory = $eventFactory;
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

        $this->eventBus->dispatch($this->eventFactory->newCreatedEvent($comment));
    }
}
