<?php

namespace Acme\Application\Blog\Command\Post\Handler;

use Acme\Application\Blog\Command\Post\CreatePost;
use Acme\Application\Blog\Event\EventBus;
use Acme\Application\Blog\Event\Post\PostCreated;
use Acme\Domain\Blog\Repository\PostRepository;
use DateTime;

class CreatePostHandler
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

        $this->eventBus->dispatch(new PostCreated($post->getId()));
    }
}
