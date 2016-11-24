<?php

namespace Acme\Application\Blog\Event\Post\Subscriber;

use Acme\Application\Blog\Event\Exception\UnexpectedEventException;
use Acme\Application\Blog\Event\Post\PostCreated;
use Acme\Application\Blog\Event\Post\PostDeleted;
use Acme\Domain\Blog\Repository\PostCategoryCounterRepository;
use Acme\Domain\Blog\Repository\PostRepository;
use Acme\Domain\Blog\Repository\PostTagCounterRepository;
use DateTime;

class IncrementPostTaxonomyCounterSubscriber
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var PostCategoryCounterRepository
     */
    private $categoryCounterRepository;

    /**
     * @var PostTagCounterRepository
     */
    private $tagCounterRepository;

    /**
     * @param PostRepository $postRepository
     * @param PostCategoryCounterRepository $categoryCounterRepository
     * @param PostTagCounterRepository $tagCounterRepository
     */
    public function __construct(
        PostRepository $postRepository,
        PostCategoryCounterRepository $categoryCounterRepository,
        PostTagCounterRepository $tagCounterRepository
    ) {
        $this->postRepository = $postRepository;
        $this->tagCounterRepository = $tagCounterRepository;
        $this->categoryCounterRepository = $categoryCounterRepository;
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

        $this->categoryCounterRepository->incrementCount($post->getCategory(), $increment);
        $this->categoryCounterRepository->incrementCountThatDay(
            $post->getCategory(),
            new DateTime(),
            $increment
        );
        foreach ($post->getTags() as $tag) {
            $this->tagCounterRepository->incrementCount($tag, $increment);
            $this->tagCounterRepository->incrementCountThatDay($tag, new DateTime(), $increment);
        }
    }
}
