<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM\Normalized;

use Acme\Domain\Blog\Repository\CommentCounterRepository as CommentCounterRepositoryInterface;
use DateTime;
use Doctrine\ORM\EntityRepository;

class CommentCounterRepository implements CommentCounterRepositoryInterface
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
        $builder = $this->repository->createQueryBuilder('comment')
            ->select('COUNT(comment)');

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @inheritDoc
     */
    public function countThatDay(DateTime $day)
    {
        $builder = $this->repository->createQueryBuilder('comment')
            ->select('COUNT(comment)')
            ->where('comment.postedAt = :day')
            ->setParameter('day', $day);

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @inheritDoc
     */
    public function countBetween(DateTime $from, DateTime $to)
    {
        $builder = $this->repository->createQueryBuilder('comment')
            ->select('COUNT(comment)')
            ->where('comment.postedAt >= :from')
            ->andWhere('comment.postedAt <= :to')
            ->setParameter('to', $to)
            ->setParameter('from', $from);

        return intval($builder->getQuery()->getSingleScalarResult());
    }
}
