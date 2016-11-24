<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM\Normalized;

use Acme\Domain\Blog\Model\Tag;
use Acme\Domain\Blog\Repository\PostTagCounterRepository as PostTagCounterRepositoryInterface;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class PostTagCounterRepository implements PostTagCounterRepositoryInterface
{
    /**
     * @var EntityRepository
     */
    private $postRepository;

    /**
     * @param EntityRepository $postRepository
     */
    public function __construct(EntityRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @inheritDoc
     */
    public function incrementCount(Tag $tag, $incr = 1)
    {
        //nothing to do
    }

    /**
     * @inheritDoc
     */
    public function incrementCountThatDay(Tag $tag, DateTime $day, $incr = 1)
    {
        //nothing to do
    }

    /**
     * @inheritDoc
     */
    public function count(Tag $tag)
    {
        $builder = $this->postRepository->createQueryBuilder('post')
            ->select('COUNT(post)')
            ->innerJoin('post.tags', 'tag', Join::WITH, 'tag = :tag')
            ->where('post.category = :category')
            ->setParameter('tag', $tag);

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @inheritDoc
     */
    public function countThatDay(Tag $tag, DateTime $day)
    {
        $builder = $this->postRepository->createQueryBuilder('post')
            ->select('COUNT(post)')
            ->where('post.postedAt = :day')
            ->innerJoin('post.tags', 'tag', Join::WITH, 'tag = :tag')
            ->setParameter('tag', $tag)
            ->setParameter('day', $day);

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @inheritDoc
     */
    public function countBetween(Tag $tag, DateTime $from, DateTime $to)
    {
        $builder = $this->postRepository->createQueryBuilder('post')
            ->select('COUNT(post)')
            ->innerJoin('post.tags', 'tag', Join::WITH, 'tag = :tag')
            ->where('post.postedAt >= :from')
            ->andWhere('post.postedAt <= :to')
            ->setParameter('tag', $tag)
            ->setParameter('to', $to)
            ->setParameter('from', $from);

        return intval($builder->getQuery()->getSingleScalarResult());
    }
}
