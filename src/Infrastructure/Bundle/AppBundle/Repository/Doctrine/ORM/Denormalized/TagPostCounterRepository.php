<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM\Denormalized;

use Acme\Domain\Blog\Model\Tag;
use Acme\Domain\Blog\Repository\TagPostCounterRepository as TagPostCounterRepositoryInterface;
use Acme\Infrastructure\Bundle\AppBundle\Entity\Repository\CounterRepository;
use DateTime;

class TagPostCounterRepository implements TagPostCounterRepositoryInterface
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
    public function incrementCount(Tag $tag, $incr = 1)
    {
        $this->repository->increment('post_by_tag', $incr, $tag);
    }

    /**
     * @inheritDoc
     */
    public function incrementCountThatDay(Tag $tag, DateTime $day, $incr = 1)
    {
        $this->repository->incrementThatDay('post_by_tag', $day, $incr, $tag);
    }

    /**
     * @inheritDoc
     */
    public function count(Tag $tag)
    {
        return $this->repository->count('post_by_tag', $tag);
    }

    /**
     * @inheritDoc
     */
    public function countThatDay(Tag $tag, DateTime $day)
    {
        return $this->repository->countThatDay('post_by_tag', $day, $tag);
    }

    /**
     * @inheritDoc
     */
    public function countBetween(Tag $tag, DateTime $from, DateTime $to)
    {
        return $this->repository->countBetween('post_by_tag', $from, $to, $tag);
    }
}
