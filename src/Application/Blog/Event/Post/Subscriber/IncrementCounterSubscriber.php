<?php

namespace Acme\Application\Blog\Event\Post\Subscriber;

use Acme\Application\Blog\Event\Post\PostCreated;
use Acme\Application\Blog\Event\Post\PostDeleted;
use Acme\Domain\Blog\Model\Category;
use Acme\Domain\Blog\Model\Tag;
use Acme\Domain\Blog\Repository\CategoryRepository;
use Acme\Domain\Blog\Repository\PostCategoryCounterRepository;
use Acme\Domain\Blog\Repository\PostCounterRepository;
use Acme\Domain\Blog\Repository\PostTagCounterRepository;
use Acme\Domain\Blog\Repository\TagRepository;
use DateTime;

class IncrementCounterSubscriber
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
     * @var PostCounterRepository
     */
    private $counterRepository;

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
     * @param PostCounterRepository         $counterRepository
     * @param PostCategoryCounterRepository $categoryCounterRepository
     * @param PostTagCounterRepository      $tagCounterRepository
     */
    public function __construct(
        TagRepository $tagRepository,
        CategoryRepository $categoryRepository,
        PostCounterRepository $counterRepository,
        PostCategoryCounterRepository $categoryCounterRepository,
        PostTagCounterRepository $tagCounterRepository
    ) {
        $this->tagRepository = $tagRepository;
        $this->categoryRepository = $categoryRepository;
        $this->counterRepository = $counterRepository;
        $this->categoryCounterRepository = $categoryCounterRepository;
        $this->tagCounterRepository = $tagCounterRepository;
    }

    /**
     * @param PostCreated $event
     */
    public function created(PostCreated $event)
    {
        $date = $this->getDate($event->getData());
        $category = $this->getCategory($event->getData());
        $tags = $this->getTags($event->getData());

        $this->incrementPost($date, 1);

        $this->incrementCategory($category, $date, 1);

        $this->incrementTags($tags, $date, 1);
    }

    /**
     * @param PostDeleted $event
     */
    public function deleted(PostDeleted $event)
    {
        $date = $this->getDate($event->getData());
        $category = $this->getCategory($event->getData());
        $tags = $this->getTags($event->getData());

        $this->incrementPost($date, -1);

        $this->incrementCategory($category, $date, -1);

        $this->incrementTags($tags, $date, -1);
    }

    /**
     * @param array $data
     *
     * @return DateTime
     */
    private function getDate(array $data)
    {
        return new DateTime($data['posted_at']);
    }

    /**
     * @param array $data
     *
     * @return Category
     */
    private function getCategory(array $data)
    {
        return $this->categoryRepository->getById($data['category_id']);
    }

    /**
     * @param array $data
     *
     * @return Tag[]
     */
    private function getTags(array $data)
    {
        return array_map(
            function ($id) {
                return $this->tagRepository->getById($id);
            },
            $data['tag_ids']
        );
    }

    /**
     * @param DateTime $date
     * @param int      $value
     */
    private function incrementPost(DateTime $date, $value)
    {
        $this->counterRepository->incrementCount($value);
        $this->counterRepository->incrementCountThatDay($date, $value);
    }

    /**
     * @param Category $category
     * @param DateTime $date
     * @param int      $value
     */
    private function incrementCategory(Category $category, DateTime $date, $value)
    {
        $this->categoryCounterRepository->incrementCount($category, $value);
        $this->categoryCounterRepository->incrementCountThatDay($category, $date, $value);
    }

    /**
     * @param Tag[]    $tags
     * @param DateTime $date
     * @param int      $value
     */
    private function incrementTags($tags, DateTime $date, $value)
    {
        foreach ($tags as $tag) {
            $this->tagCounterRepository->incrementCount($tag, $value);
            $this->tagCounterRepository->incrementCountThatDay($tag, $date, $value);
        }
    }
}
