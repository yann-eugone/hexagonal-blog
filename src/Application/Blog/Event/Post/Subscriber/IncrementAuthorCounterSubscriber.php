<?php

namespace Acme\Application\Blog\Event\Post\Subscriber;

use Acme\Application\Blog\Event\Exception\UnexpectedEventException;
use Acme\Application\Blog\Event\Post\PostCreated;
use Acme\Application\Blog\Event\Post\PostDeleted;
use Acme\Domain\Blog\Repository\PostCounterRepository;
use Acme\Domain\Blog\Repository\PostRepository;
use DateTime;

class IncrementAuthorCounterSubscriber
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var PostCounterRepository
     */
    private $counterRepository;

    /**
     * @param PostRepository        $postRepository
     * @param PostCounterRepository $counterRepository
     */
    public function __construct(PostRepository $postRepository, PostCounterRepository $counterRepository)
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

        $this->counterRepository->incrementCount($increment);
        $this->counterRepository->incrementCountThatDay(new DateTime(), $increment);
        $this->counterRepository->incrementCountForAuthor($post->getAuthor(), $increment);
        $this->counterRepository->incrementCountForAuthorThatDay($post->getAuthor(), new DateTime(), $increment);
    }
}
