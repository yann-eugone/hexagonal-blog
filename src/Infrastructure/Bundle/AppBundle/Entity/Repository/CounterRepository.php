<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Entity\Repository;

use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Model\Category;
use Acme\Domain\Blog\Model\Tag;
use Acme\Infrastructure\Bundle\AppBundle\Entity\Counter;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;

class CounterRepository extends EntityRepository
{
    /**
     * @param string            $type
     * @param Category|Tag|null $subject
     * @param Author|null       $author
     *
     * @return int
     */
    public function count($type, $subject = null, $author = null)
    {
        $builder = $this->createSearchQueryBuilder($type, $subject, $author);
        $builder->select('SUM(counter.value)');

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @param string            $type
     * @param DateTime          $date
     * @param Category|Tag|null $subject
     * @param Author|null       $author
     *
     * @return int
     */
    public function countThatDay($type, DateTime $date, $subject = null, $author = null)
    {
        $builder = $this->createSearchQueryBuilder($type, $subject, $author);
        $this->addExactDateCondition($builder, $date);
        $builder->select('SUM(counter.value)');

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @param string            $type
     * @param DateTime          $from
     * @param DateTime          $to
     * @param Category|Tag|null $subject
     * @param Author|null       $author
     *
     * @return int
     */
    public function countBetween($type, DateTime $from, DateTime $to, $subject = null, $author = null)
    {
        $builder = $this->createSearchQueryBuilder($type, $subject, $author);
        $this->addDateRangeCondition($builder, $from, $to);
        $builder->select('SUM(counter.value)');

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @param int               $value
     * @param string            $type
     * @param Category|Tag|null $subject
     * @param Author|null       $author
     */
    public function increment($type, $value, $subject = null, $author = null)
    {
        $counter = $this->getCounter($type, $subject, $author);
        $counter->increment($value);
        $this->getEntityManager()->persist($counter);
        $this->getEntityManager()->flush($counter);
    }

    /**
     * @param int               $value
     * @param DateTime          $date
     * @param string            $type
     * @param Category|Tag|null $subject
     * @param Author|null       $author
     */
    public function incrementThatDay($type, DateTime $date, $value, $subject = null, $author = null)
    {
        $counter = $this->getCounter($type, $subject, $author, $date);
        $counter->increment($value);
        $this->getEntityManager()->persist($counter);
        $this->getEntityManager()->flush($counter);
    }

    /**
     * @param string            $type
     * @param Category|Tag|null $subject
     * @param Author|null       $author
     * @param DateTime|null     $date
     *
     * @return Counter
     */
    private function getCounter($type, $subject = null, $author = null, $date = null)
    {
        $builder = $this->createSearchQueryBuilder($type, $subject, $author);
        $this->addExactDateCondition($builder, $date);

        $counter = null;
        try {
            $counter = $builder->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
        }

        if (!$counter instanceof Counter) {
            $counter = new Counter($type, $date, $subject, $author);
        }

        return $counter;
    }

    /**
     * @param string       $type
     * @param Category|Tag $subject
     * @param Author|null  $author
     *
     * @return QueryBuilder
     */
    private function createSearchQueryBuilder($type, $subject = null, $author = null)
    {
        $builder = $this->createQueryBuilder('counter');
        $builder->where('counter.type = :type')->setParameter('type', $type);

        if ($subject instanceof Category) {
            $builder
                ->andWhere('counter.category = :category')
                ->setParameter('category', $subject)
            ;
        } elseif ($subject instanceof Tag) {
            $builder
                ->andWhere('counter.tag = :tag')
                ->setParameter('tag', $subject)
            ;
        }

        if ($author instanceof Author) {
            $builder
                ->andWhere('counter.author = :author')
                ->setParameter('author', $author)
            ;
        } else {
            $builder->andWhere('counter.author IS NULL');
        }

        return $builder;
    }

    /**
     * @param QueryBuilder  $builder
     * @param DateTime|null $date
     */
    private function addExactDateCondition(QueryBuilder $builder, $date = null)
    {
        if ($date instanceof DateTime) {
            $builder->andWhere('counter.date = :date')->setParameter('date', $date->format('Y-m-d'));
        } else {
            $builder->andWhere('counter.date IS NULL');
        }
    }

    /**
     * @param QueryBuilder $builder
     * @param DateTime     $from
     * @param DateTime     $to
     */
    private function addDateRangeCondition(QueryBuilder $builder, DateTime $from, DateTime $to)
    {
        $builder->andWhere('counter.date >= :from')
            ->andWhere('counter.date <= :to')
            ->setParameter('to', $to)
            ->setParameter('from', $from)
        ;
    }
}
