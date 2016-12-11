<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM\Denormalized;

use Acme\Domain\Blog\Model\Category;
use Acme\Domain\Blog\Repository\CategoryPostCounterRepository as CategoryPostCounterRepositoryInterface;
use Acme\Infrastructure\Bundle\AppBundle\Entity\Repository\CounterRepository;
use DateTime;

class CategoryPostCounterRepository implements CategoryPostCounterRepositoryInterface
{
    /**
     * @var CounterRepository
     */
    private $repository;

    /**
     * @param CounterRepository $repository
     */
    public function __construct(CounterRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function incrementCount(Category $category, $incr = 1)
    {
        $this->repository->increment('post_by_category', $incr, $category);
    }

    /**
     * @inheritDoc
     */
    public function incrementCountThatDay(Category $category, DateTime $day, $incr = 1)
    {
        $this->repository->incrementThatDay('post_by_category', $day, $incr, $category);
    }

    /**
     * @inheritDoc
     */
    public function count(Category $category)
    {
        return $this->repository->count('post_by_category', $category);
    }

    /**
     * @inheritDoc
     */
    public function countThatDay(Category $category, DateTime $day)
    {
        return $this->repository->countThatDay('post_by_category', $day, $category);
    }

    /**
     * @inheritDoc
     */
    public function countBetween(Category $category, DateTime $from, DateTime $to)
    {
        return $this->repository->countBetween('post_by_category', $from, $to, $category);
    }
}
