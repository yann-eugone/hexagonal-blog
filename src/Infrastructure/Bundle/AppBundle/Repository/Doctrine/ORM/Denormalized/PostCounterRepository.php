<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM\Denormalized;

use Acme\Domain\Blog\Repository\PostCounterRepository as PostCounterRepositoryInterface;
use Acme\Infrastructure\Bundle\AppBundle\Entity\Repository\CounterRepository;
use DateTime;

class PostCounterRepository implements PostCounterRepositoryInterface
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
        $this->repository->increment('post', $incr);
    }

    /**
     * @inheritDoc
     */
    public function incrementCountThatDay(DateTime $day, $incr = 1)
    {
        $this->repository->incrementThatDay('post', $day, $incr);
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return $this->repository->count('post');
    }

    /**
     * @inheritDoc
     */
    public function countThatDay(DateTime $day)
    {
        return $this->repository->countThatDay('post', $day);
    }

    /**
     * @inheritDoc
     */
    public function countBetween(DateTime $from, DateTime $to)
    {
        return $this->repository->countBetween('post', $from, $to);
    }
}
