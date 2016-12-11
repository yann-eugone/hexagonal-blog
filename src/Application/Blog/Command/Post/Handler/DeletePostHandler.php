<?php

namespace Acme\Application\Blog\Command\Post\Handler;

use Acme\Application\Blog\Command\Post\DeletePost;
use Acme\Application\Common\Event\EventBus;
use Acme\Application\Blog\Event\Post\PostEventFactory;
use Acme\Domain\Blog\Repository\PostRepository;

class DeletePostHandler
{
    /**
     * @var PostRepository
     */
    private $repository;

    /**
     * @var PostEventFactory
     */
    private $eventFactory;

    /**
     * @var EventBus
     */
    private $eventBus;

    /**
     * @param PostRepository   $repository
     * @param PostEventFactory $eventFactory
     * @param EventBus         $eventBus
     */
    public function __construct(PostRepository $repository, PostEventFactory $eventFactory, EventBus $eventBus)
    {
        $this->repository = $repository;
        $this->eventFactory = $eventFactory;
        $this->eventBus = $eventBus;
    }

    /**
     * @param DeletePost $command
     */
    public function __invoke(DeletePost $command)
    {
        $post = $this->repository->getById($command->getId());

        $event = $this->eventFactory->postDeleted($post);

        $this->repository->delete($post);

        $this->eventBus->dispatch($event);
    }
}
