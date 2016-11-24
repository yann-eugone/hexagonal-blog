<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM\Denormalized;

use Acme\Domain\Blog\Repository\CommentCounterRepository as CommentCounterRepositoryInterface;
use Acme\Infrastructure\Bundle\AppBundle\Entity\Repository\CounterRepository;
use DateTime;

class CommentCounterRepository implements CommentCounterRepositoryInterface
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
    public function incrementCount($incr = 1)
    {
        $this->repository->increment('comment', $incr);
    }

    /**
     * @inheritDoc
     */
    public function incrementCountThatDay(DateTime $day, $incr = 1)
    {
        $this->repository->incrementThatDay('comment', $day, $incr);
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return $this->repository->count('comment');
    }

    /**
     * @inheritDoc
     */
    public function countThatDay(DateTime $day)
    {
        return $this->repository->countThatDay('comment', $day);
    }

    /**
     * @inheritDoc
     */
    public function countBetween(DateTime $from, DateTime $to)
    {
        return $this->repository->countBetween('comment', $from, $to);
    }
}
