<?php

namespace Acme\Application\Blog\Command\Post\Handler;

use Acme\Application\Blog\Command\Post\UpdatePost;
use Acme\Application\Blog\Event\EventBus;
use Acme\Application\Blog\Event\Post\PostEventFactory;
use Acme\Domain\Blog\Repository\PostRepository;

class UpdatePostHandler
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
     * @param UpdatePost $command
     */
    public function __invoke(UpdatePost $command)
    {
        $post = $this->repository->getById($command->getId());

        $referencePost = clone $post;

        $post->setTitle($command->getTitle());
        $post->setSummary($command->getSummary());
        $post->setBody($command->getBody());
        $post->setCategory($command->getCategory());
        $post->setTags($command->getTags());

        $this->repository->update($post);

        $this->eventBus->dispatch($this->eventFactory->newUpdatedEvent($referencePost, $post));
    }
}
