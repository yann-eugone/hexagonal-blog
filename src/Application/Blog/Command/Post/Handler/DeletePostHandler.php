<?php

namespace Acme\Application\Blog\Command\Post\Handler;

use Acme\Application\Blog\Command\Post\DeletePost;
use Acme\Application\Blog\Event\EventBus;
use Acme\Application\Blog\Event\Post\PostDeleted;
use Acme\Domain\Blog\Repository\PostRepository;

class DeletePostHandler
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
     * @param PostRepository $repository
     * @param EventBus       $eventBus
     */
    public function __construct(PostRepository $repository, EventBus $eventBus)
    {
        $this->repository = $repository;
        $this->eventBus = $eventBus;
    }

    /**
     * @param DeletePost $command
     */
    public function __invoke(DeletePost $command)
    {
        $post = $this->repository->getById($command->getId());

        $this->repository->delete($post);

        $this->eventBus->dispatch(new PostDeleted($command->getId()));
    }
}
