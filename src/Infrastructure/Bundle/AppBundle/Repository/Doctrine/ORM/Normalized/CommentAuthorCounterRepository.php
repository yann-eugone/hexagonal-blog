<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM\Normalized;

use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Repository\CommentAuthorCounterRepository as CommentAuthorCounterRepositoryInterface;
use DateTime;
use Doctrine\ORM\EntityRepository;

class CommentAuthorCounterRepository implements CommentAuthorCounterRepositoryInterface
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
    public function incrementCount(Author $author, $incr = 1)
    {
        //nothing to do
    }

    /**
     * @inheritDoc
     */
    public function incrementCountThatDay(Author $author, DateTime $day, $incr = 1)
    {
        //nothing to do
    }

    /**
     * @inheritDoc
     */
    public function count(Author $author)
    {
        $builder = $this->repository->createQueryBuilder('comment')
            ->select('COUNT(comment)')
            ->where('comment.author = :author')
            ->setParameter('author', $author);

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @inheritDoc
     */
    public function countThatDay(DateTime $day, Author $author)
    {
        $builder = $this->repository->createQueryBuilder('comment')
            ->select('COUNT(comment)')
            ->where('comment.postedAt = :day')
            ->andWhere('comment.author = :author')
            ->setParameter('day', $day)
            ->setParameter('author', $author);

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @inheritDoc
     */
    public function countBetween(DateTime $from, DateTime $to, Author $author)
    {
        $builder = $this->repository->createQueryBuilder('comment')
            ->select('COUNT(comment)')
            ->where('comment.postedAt >= :from')
            ->andWhere('comment.postedAt <= :to')
            ->andWhere('comment.author <= :author')
            ->setParameter('to', $to)
            ->setParameter('from', $from)
            ->setParameter('author', $author);

        return intval($builder->getQuery()->getSingleScalarResult());
    }
}
