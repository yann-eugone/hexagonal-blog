<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM\Denormalized;

use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Repository\FavoriteAuthorCounterRepository as FavoriteAuthorCounterRepositoryInterface;
use Acme\Infrastructure\Bundle\AppBundle\Entity\Repository\CounterRepository;
use DateTime;

class FavoriteAuthorCounterRepository implements FavoriteAuthorCounterRepositoryInterface
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
    public function incrementCount(Author $author, $incr = 1)
    {
        $this->repository->increment('favorite_by_author', $incr, null, $author);
    }

    /**
     * @inheritDoc
     */
    public function incrementCountThatDay(Author $author, DateTime $day, $incr = 1)
    {
        $this->repository->incrementThatDay('favorite_by_author', $day, $incr, $author);
    }

    /**
     * @inheritDoc
     */
    public function count(Author $author)
    {
        return $this->repository->count('favorite_by_author', null, $author);
    }

    /**
     * @inheritDoc
     */
    public function countThatDay(Author $author, DateTime $day)
    {
        return $this->repository->countThatDay('favorite_by_author', $day, null, $author);
    }

    /**
     * @inheritDoc
     */
    public function countBetween(Author $author, DateTime $from, DateTime $to)
    {
        return $this->repository->countBetween('favorite_by_author', $from, $to, null, $author);
    }
}
