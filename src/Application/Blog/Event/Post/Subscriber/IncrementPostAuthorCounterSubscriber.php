<?php

namespace Acme\Application\Blog\Event\Post\Subscriber;

use Acme\Application\Blog\Event\Exception\UnexpectedEventException;
use Acme\Application\Blog\Event\Post\PostCreated;
use Acme\Application\Blog\Event\Post\PostDeleted;
use Acme\Domain\Blog\Repository\PostAuthorCounterRepository;
use Acme\Domain\Blog\Repository\PostRepository;
use DateTime;

class IncrementPostAuthorCounterSubscriber
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var PostAuthorCounterRepository
     */
    private $counterRepository;

    /**
     * @param PostRepository        $postRepository
     * @param PostAuthorCounterRepository $counterRepository
     */
    public function __construct(PostRepository $postRepository, PostAuthorCounterRepository $counterRepository)
    {
        $this->postRepository = $postRepository;
        $this->counterRepository = $counterRepository;
    }

    /**
     * @param PostCreated|PostDeleted $event
     */
    public function __invoke($event)
    {
        $incrementMap = [
            PostCreated::class => 1,
            PostDeleted::class => -1,
        ];

        if (!isset($incrementMap[get_class($event)])) {
            throw UnexpectedEventException::create($this, $event);
        }

        $post = $this->postRepository->getById($event->getId());

        $increment = $incrementMap[get_class($event)];

        $this->counterRepository->incrementCount($post->getAuthor(), $increment);
        $this->counterRepository->incrementCountThatDay($post->getAuthor(), new DateTime(), $increment);
    }
}
