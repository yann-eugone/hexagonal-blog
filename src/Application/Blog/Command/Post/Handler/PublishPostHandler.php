<?php

namespace Acme\Application\Blog\Command\Post\Handler;

use Acme\Application\Blog\Command\Post\PublishPost;
use Acme\Application\Blog\Event\EventBus;
use Acme\Application\Blog\Event\Post\PostPublished;
use Acme\Domain\Blog\Repository\PostRepository;
use DateTime;

class PublishPostHandler
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
     * @param PublishPost $command
     */
    public function __invoke(PublishPost $command)
    {
        $post = $this->repository->getById($command->getId());

        $post->setPublishedAt(new DateTime());

        $this->repository->update($post);

        $this->eventBus->dispatch(new PostPublished($command->getId()));
    }
}
