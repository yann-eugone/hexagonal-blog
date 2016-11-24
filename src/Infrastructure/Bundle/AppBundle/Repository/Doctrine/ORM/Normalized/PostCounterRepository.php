<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM\Normalized;

use Acme\Domain\Blog\Repository\PostCounterRepository as PostCounterRepositoryInterface;
use DateTime;
use Doctrine\ORM\EntityRepository;

class PostCounterRepository implements PostCounterRepositoryInterface
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
    public function incrementCount($incr = 1)
    {
        //nothing to do
    }

    /**
     * @inheritDoc
     */
    public function incrementCountThatDay(DateTime $day, $incr = 1)
    {
        //nothing to do
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        $builder = $this->repository->createQueryBuilder('post')
            ->select('COUNT(post)');

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @inheritDoc
     */
    public function countThatDay(DateTime $day)
    {
        $builder = $this->repository->createQueryBuilder('post')
            ->select('COUNT(post)')
            ->where('post.postedAt = :day')
            ->setParameter('day', $day);

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @inheritDoc
     */
    public function countBetween(DateTime $from, DateTime $to)
    {
        $builder = $this->repository->createQueryBuilder('post')
            ->select('COUNT(post)')
            ->where('post.postedAt >= :from')
            ->andWhere('post.postedAt <= :to')
            ->setParameter('to', $to)
            ->setParameter('from', $from);

        return intval($builder->getQuery()->getSingleScalarResult());
    }
}
