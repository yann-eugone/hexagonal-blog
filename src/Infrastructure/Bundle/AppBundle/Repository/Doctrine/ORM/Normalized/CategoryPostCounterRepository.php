<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM\Normalized;

use Acme\Domain\Blog\Model\Category;
use Acme\Domain\Blog\Repository\CategoryPostCounterRepository as CategoryPostCounterRepositoryInterface;
use DateTime;
use Doctrine\ORM\EntityRepository;

class CategoryPostCounterRepository implements CategoryPostCounterRepositoryInterface
{
    /**
     * @var EntityRepository
     */
    private $postRepository;

    /**
     * @param EntityRepository $postRepository
     */
    public function __construct(EntityRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @inheritDoc
     */
    public function incrementCount(Category $category, $incr = 1)
    {
        //nothing to do
    }

    /**
     * @inheritDoc
     */
    public function incrementCountThatDay(Category $category, DateTime $day, $incr = 1)
    {
        //nothing to do
    }

    /**
     * @inheritDoc
     */
    public function count(Category $category)
    {
        $builder = $this->postRepository->createQueryBuilder('post')
            ->select('COUNT(post)')
            ->where('post.category = :category')
            ->setParameter('category', $category);

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @inheritDoc
     */
    public function countThatDay(Category $category, DateTime $day)
    {
        $builder = $this->postRepository->createQueryBuilder('post')
            ->select('COUNT(post)')
            ->where('post.category = :category')
            ->andWhere('post.postedAt = :day')
            ->setParameter('category', $category)
            ->setParameter('day', $day);

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @inheritDoc
     */
    public function countBetween(Category $category, DateTime $from, DateTime $to)
    {
        $builder = $this->postRepository->createQueryBuilder('post')
            ->select('COUNT(post)')
            ->where('post.category = :category')
            ->andWhere('post.postedAt >= :from')
            ->andWhere('post.postedAt <= :to')
            ->setParameter('category', $category)
            ->setParameter('to', $to)
            ->setParameter('from', $from);

        return intval($builder->getQuery()->getSingleScalarResult());
    }
}
