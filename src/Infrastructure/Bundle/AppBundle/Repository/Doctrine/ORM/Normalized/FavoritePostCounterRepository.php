<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM\Normalized;

use Acme\Domain\Blog\Model\Post;
use Acme\Domain\Blog\Repository\FavoritePostCounterRepository as FavoritePostCounterRepositoryInterface;
use DateTime;
use Doctrine\ORM\EntityRepository;

class FavoritePostCounterRepository implements FavoritePostCounterRepositoryInterface
{
    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @param EntityRepository $repository
     */
    public function __construct(EntityRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function incrementCount(Post $post, $incr = 1)
    {
        //nothing to do
    }

    /**
     * @inheritDoc
     */
    public function incrementCountThatDay(Post $post, DateTime $day, $incr = 1)
    {
        //nothing to do
    }

    /**
     * @inheritDoc
     */
    public function count(Post $post)
    {
        $builder = $this->repository->createQueryBuilder('favorite')
            ->select('COUNT(favorite)')
            ->where('favorite.post = :post')
            ->setParameter('post', $post);

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @inheritDoc
     */
    public function countThatDay(Post $post, DateTime $day)
    {
        $builder = $this->repository->createQueryBuilder('favorite')
            ->select('COUNT(favorite)')
            ->where('favorite.postedAt = :day')
            ->andWhere('favorite.post = :post')
            ->setParameter('day', $day)
            ->setParameter('post', $post);

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @inheritDoc
     */
    public function countBetween(Post $post, DateTime $from, DateTime $to)
    {
        $builder = $this->repository->createQueryBuilder('favorite')
            ->select('COUNT(favorite)')
            ->where('favorite.post = :post')
            ->andWhere('favorite.postedAt >= :from')
            ->andWhere('favorite.postedAt <= :to')
            ->setParameter('to', $to)
            ->setParameter('from', $from)
            ->setParameter('post', $post);

        return intval($builder->getQuery()->getSingleScalarResult());
    }
}
