<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM\Denormalized;

use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Repository\PostAuthorCounterRepository as PostAuthorCounterRepositoryInterface;
use Acme\Infrastructure\Bundle\AppBundle\Entity\Repository\CounterRepository;
use DateTime;

class PostAuthorCounterRepository implements PostAuthorCounterRepositoryInterface
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
        $this->repository->increment('post_by_author', $incr, null, $author);
    }

    /**
     * @inheritDoc
     */
    public function incrementCountThatDay(Author $author, DateTime $day, $incr = 1)
    {
        $this->repository->incrementThatDay('post_by_author', $day, $incr, null, $author);
    }

    /**
     * @inheritDoc
     */
    public function count(Author $author)
    {
        return $this->repository->count('post_by_author', null, $author);
    }

    /**
     * @inheritDoc
     */
    public function countThatDay(DateTime $day, Author $author)
    {
        return $this->repository->countThatDay('post_by_author', $day, null, $author);
    }

    /**
     * @inheritDoc
     */
    public function countBetween(DateTime $from, DateTime $to, Author $author)
    {
        return $this->repository->countBetween('post_by_author', $from, $to, null, $author);
    }
}
