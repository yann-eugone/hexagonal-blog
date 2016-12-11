<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM\Normalized;

use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Repository\AuthorPostCounterRepository as AuthorPostCounterRepositoryInterface;
use DateTime;
use Doctrine\ORM\EntityRepository;

class AuthorPostCounterRepository implements AuthorPostCounterRepositoryInterface
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
        $builder = $this->repository->createQueryBuilder('post')
            ->select('COUNT(post)')
            ->where('post.author = :author')
            ->setParameter('author', $author);

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @inheritDoc
     */
    public function countThatDay(DateTime $day, Author $author)
    {
        $builder = $this->repository->createQueryBuilder('post')
            ->select('COUNT(post)')
            ->where('post.postedAt = :day')
            ->andWhere('post.author = :author')
            ->setParameter('day', $day)
            ->setParameter('author', $author);

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @inheritDoc
     */
    public function countBetween(DateTime $from, DateTime $to, Author $author)
    {
        $builder = $this->repository->createQueryBuilder('post')
            ->select('COUNT(post)')
            ->where('post.postedAt >= :from')
            ->andWhere('post.postedAt <= :to')
            ->andWhere('post.author = :author')
            ->setParameter('to', $to)
            ->setParameter('from', $from)
            ->setParameter('author', $author);

        return intval($builder->getQuery()->getSingleScalarResult());
    }
}
