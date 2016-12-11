<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM\Denormalized;

use Acme\Domain\Blog\Model\Post;
use Acme\Domain\Blog\Repository\PostFavoriteCounterRepository as PostFavoriteCounterRepositoryInterface;
use Acme\Infrastructure\Bundle\AppBundle\Entity\Repository\CounterRepository;
use DateTime;

class PostFavoriteCounterRepository implements PostFavoriteCounterRepositoryInterface
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
    public function incrementCount(Post $post, $incr = 1)
    {
        $this->repository->increment('favorite_by_post', $incr, $post);
    }

    /**
     * @inheritDoc
     */
    public function incrementCountThatDay(Post $post, DateTime $day, $incr = 1)
    {
        $this->repository->incrementThatDay('favorite_by_post', $day, $incr, $post);
    }

    /**
     * @inheritDoc
     */
    public function count(Post $post)
    {
        return $this->repository->count('favorite_by_post', $post);
    }

    /**
     * @inheritDoc
     */
    public function countThatDay(Post $post, DateTime $day)
    {
        return $this->repository->countThatDay('favorite_by_post', $day, $post);
    }

    /**
     * @inheritDoc
     */
    public function countBetween(Post $post, DateTime $from, DateTime $to)
    {
        return $this->repository->countBetween('favorite_by_post', $from, $to, $post);
    }
}
