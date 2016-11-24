<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM\Denormalized;

use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Repository\CommentAuthorCounterRepository as CommentAuthorCounterRepositoryInterface;
use Acme\Infrastructure\Bundle\AppBundle\Entity\Repository\CounterRepository;
use DateTime;

class CommentAuthorCounterRepository implements CommentAuthorCounterRepositoryInterface
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
        $this->repository->increment('comment_by_author', $incr, null, $author);
    }

    /**
     * @inheritDoc
     */
    public function incrementCountThatDay(Author $author, DateTime $day, $incr = 1)
    {
        $this->repository->incrementThatDay('comment_by_author', $day, $incr, $author);
    }

    /**
     * @inheritDoc
     */
    public function count(Author $author)
    {
        return $this->repository->count('comment_by_author', null, $author);
    }

    /**
     * @inheritDoc
     */
    public function countThatDay(DateTime $day, Author $author)
    {
        return $this->repository->countThatDay('comment_by_author', $day, null, $author);
    }

    /**
     * @inheritDoc
     */
    public function countBetween(DateTime $from, DateTime $to, Author $author)
    {
        return $this->repository->countBetween('comment_by_author', $from, $to, null, $author);
    }
}
