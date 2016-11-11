<?php

namespace Acme\Application\Blog\Event\Post\Subscriber;

use Acme\Application\Blog\Event\Exception\UnexpectedEventException;
use Acme\Application\Blog\Event\Post\PostCreated;
use Acme\Application\Blog\Event\Post\PostDeleted;
use Acme\Domain\Blog\Repository\CategoryCounterRepository;
use Acme\Domain\Blog\Repository\PostRepository;
use Acme\Domain\Blog\Repository\TagCounterRepository;
use DateTime;

class IncrementTaxonomyCounterSubscriber
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var CategoryCounterRepository
     */
    private $categoryCounterRepository;

    /**
     * @var TagCounterRepository
     */
    private $tagCounterRepository;

    /**
     * @param PostRepository            $postRepository
     * @param CategoryCounterRepository $categoryCounterRepository
     * @param TagCounterRepository      $tagCounterRepository
     */
    public function __construct(
        PostRepository $postRepository,
        CategoryCounterRepository $categoryCounterRepository,
        TagCounterRepository $tagCounterRepository
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

        $this->categoryCounterRepository->incrementCategoryCount($post->getCategory(), $increment);
        $this->categoryCounterRepository->incrementCategoryCountThatDay($post->getCategory(), new DateTime(), $increment);
        foreach ($post->getTags() as $tag) {
            $this->tagCounterRepository->incrementTagCount($tag, $increment);
            $this->tagCounterRepository->incrementTagCountThatDay($tag, new DateTime(), $increment);
        }
    }
}
