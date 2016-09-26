<?php

namespace Acme\Application\Blog\Command\Post\Handler;

use Acme\Application\Blog\Command\Post\UpdatePost;
use Acme\Application\Blog\Event\EventBus;
use Acme\Application\Blog\Event\Post\PostUpdated;
use Acme\Domain\Blog\Repository\PostRepository;

class UpdatePostHandler
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
     * @param UpdatePost $command
     */
    public function __invoke(UpdatePost $command)
    {
        $post = $this->repository->getById($command->getId());

        $post->setTitle($command->getTitle());
        $post->setSummary($command->getSummary());
        $post->setBody($command->getBody());

        $this->repository->update($post);

        $this->eventBus->dispatch(new PostUpdated($post->getId()));
    }
}
