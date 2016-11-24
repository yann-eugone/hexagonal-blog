<?php

namespace Acme\Application\Blog\Event\Post\Subscriber;

use Acme\Application\Blog\Event\Post\PostCreated;
use Acme\Application\Blog\Event\Post\PostDeleted;
use Acme\Domain\Blog\Repository\CategoryRepository;
use Acme\Domain\Blog\Repository\PostCategoryCounterRepository;
use Acme\Domain\Blog\Repository\PostTagCounterRepository;
use Acme\Domain\Blog\Repository\TagRepository;
use DateTime;

class IncrementPostTaxonomyCounterSubscriber
{
    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var PostCategoryCounterRepository
     */
    private $categoryCounterRepository;

    /**
     * @var PostTagCounterRepository
     */
    private $tagCounterRepository;

    /**
     * @param TagRepository                 $tagRepository
     * @param CategoryRepository            $categoryRepository
     * @param PostCategoryCounterRepository $categoryCounterRepository
     * @param PostTagCounterRepository      $tagCounterRepository
     */
    public function __construct(
        TagRepository $tagRepository,
        CategoryRepository $categoryRepository,
        PostCategoryCounterRepository $categoryCounterRepository,
        PostTagCounterRepository $tagCounterRepository
    ) {
        $this->tagRepository = $tagRepository;
        $this->categoryRepository = $categoryRepository;
        $this->categoryCounterRepository = $categoryCounterRepository;
        $this->tagCounterRepository = $tagCounterRepository;
    }

    /**
     * @param PostCreated $event
     */
    public function created(PostCreated $event)
    {
        $category = $this->categoryRepository->getById($event->getData()['category_id']);
        $date = new DateTime($event->getData()['posted_at']);

        $this->categoryCounterRepository->incrementCount($category, 1);
        $this->categoryCounterRepository->incrementCountThatDay($category, $date, 1);

        foreach ($event->getData()['tag_ids'] as $tag) {
            $tag = $this->tagRepository->getById($tag);
            $this->tagCounterRepository->incrementCount($tag, 1);
            $this->tagCounterRepository->incrementCountThatDay($tag, $date, 1);
        }
    }

    /**
     * @param PostDeleted $event
     */
    public function deleted(PostDeleted $event)
    {
        $category = $this->categoryRepository->getById($event->getData()['category_id']);
        $date = new DateTime($event->getData()['posted_at']);

        $this->categoryCounterRepository->incrementCount($category, -1);
        $this->categoryCounterRepository->incrementCountThatDay($category, $date, -1);

        foreach ($event->getData()['tag_ids'] as $tag) {
            $tag = $this->tagRepository->getById($tag);
            $this->tagCounterRepository->incrementCount($tag, -1);
            $this->tagCounterRepository->incrementCountThatDay($tag, $date, -1);
        }
    }
}
