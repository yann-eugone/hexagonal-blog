<?php

namespace Acme\Application\Blog\Command\Post\Handler;

use Acme\Application\Blog\Command\Post\CreatePost;
use Acme\Application\Common\Event\EventBus;
use Acme\Application\Blog\Event\Post\PostEventFactory;
use Acme\Domain\Blog\Repository\PostRepository;
use DateTime;

class CreatePostHandler
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
     * @param CreatePost $command
     */
    public function __invoke(CreatePost $command)
    {
        $post = $this->repository->instance();

        $command->setPost($post);

        $post->setTitle($command->getTitle());
        $post->setSummary($command->getSummary());
        $post->setBody($command->getBody());
        $post->setCategory($command->getCategory());
        $post->setTags($command->getTags());
        $post->setAuthor($command->getAuthor());
        $post->setPostedAt(new DateTime());

        $this->repository->create($post);

        $this->eventBus->dispatch($this->eventFactory->newCreatedEvent($post));
    }
}
