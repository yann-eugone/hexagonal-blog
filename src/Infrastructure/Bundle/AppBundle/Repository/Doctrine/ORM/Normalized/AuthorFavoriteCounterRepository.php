<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM\Normalized;

use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Repository\AuthorFavoriteCounterRepository as AuthorFavoriteCounterRepositoryInterface;
use DateTime;
use Doctrine\ORM\EntityRepository;

class AuthorFavoriteCounterRepository implements AuthorFavoriteCounterRepositoryInterface
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
        $builder = $this->repository->createQueryBuilder('favorite')
            ->select('COUNT(favorite)')
            ->where('favorite.author = :author')
            ->setParameter('author', $author);

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @inheritDoc
     */
    public function countThatDay(Author $author, DateTime $day)
    {
        $builder = $this->repository->createQueryBuilder('favorite')
            ->select('COUNT(favorite)')
            ->where('favorite.postedAt = :day')
            ->andWhere('favorite.author = :author')
            ->setParameter('day', $day)
            ->setParameter('author', $author);

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @inheritDoc
     */
    public function countBetween(Author $author, DateTime $from, DateTime $to)
    {
        $builder = $this->repository->createQueryBuilder('favorite')
            ->select('COUNT(favorite)')
            ->where('favorite.author = :author')
            ->andWhere('favorite.postedAt >= :from')
            ->andWhere('favorite.postedAt <= :to')
            ->setParameter('to', $to)
            ->setParameter('from', $from)
            ->setParameter('author', $author);

        return intval($builder->getQuery()->getSingleScalarResult());
    }
}
