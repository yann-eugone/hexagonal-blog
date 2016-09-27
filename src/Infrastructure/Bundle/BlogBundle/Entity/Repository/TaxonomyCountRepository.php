<?php

namespace Acme\Infrastructure\Bundle\BlogBundle\Entity\Repository;

use Acme\Domain\Blog\Model\Category;
use Acme\Domain\Blog\Model\Tag;
use Acme\Domain\Blog\Repository\CategoryCounterRepository;
use Acme\Domain\Blog\Repository\TagCounterRepository;
use Acme\Infrastructure\Bundle\BlogBundle\Entity\TaxonomyCount;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class TaxonomyCountRepository extends EntityRepository implements TagCounterRepository, CategoryCounterRepository
{
    /**
     * @inheritDoc
     */
    public function incrementCategoryCount(Category $category, $incr = 1)
    {
        $this->increment(
            $this->getCount($category),
            $incr
        );
    }

    /**
     * @inheritDoc
     */
    public function incrementCategoryCountThatDay(Category $category, DateTime $day, $incr = 1)
    {
        $this->increment(
            $this->getCount($category, $day),
            $incr
        );
    }

    /**
     * @inheritDoc
     */
    public function countCategoryThatDay(Category $category, DateTime $day)
    {
        return $this->getCount($category, $day)->getValue();
    }

    /**
     * @inheritDoc
     */
    public function countCategoryBetween(Category $category, DateTime $from, DateTime $to)
    {
        return $this->countBetween($category, $from, $to);
    }

    /**
     * @inheritDoc
     */
    public function incrementTagCount(Tag $tag, $incr = 1)
    {
        $this->increment(
            $this->getCount($tag),
            $incr
        );
    }

    /**
     * @inheritDoc
     */
    public function incrementTagCountThatDay(Tag $tag, DateTime $day, $incr = 1)
    {
        $this->increment(
            $this->getCount($tag, $day),
            $incr
        );
    }

    /**
     * @inheritDoc
     */
    public function countTagThatDay(Tag $tag, DateTime $day)
    {
        return $this->getCount($tag, $day)->getValue();
    }

    /**
     * @inheritDoc
     */
    public function countTagBetween(Tag $tag, DateTime $from, DateTime $to)
    {
        return $this->countBetween($tag, $from, $to);
    }

    /**
     * @param Category|Tag  $taxonomy
     * @param DateTime|null $date
     *
     * @return TaxonomyCount
     */
    public function getCount($taxonomy, $date = null)
    {
        $builder = $this->createQueryBuilder('taxonomy_count');

        $parameters = [];

        if ($taxonomy instanceof Category) {
            $builder->andWhere('taxonomy_count.category = :category');
            $parameters['category'] = $taxonomy;
        } elseif ($taxonomy instanceof Tag) {
            $builder->andWhere('taxonomy_count.tag = :tag');
            $parameters['tag'] = $taxonomy;
        }

        if ($date instanceof DateTime) {
            $builder->andWhere('taxonomy_count.date = :date');
            $parameters['date'] = $date->format('Y-m-d');
        } else {
            $builder->andWhere('taxonomy_count.date IS NULL');
        }

        $count = null;
        try {
            $count = $builder->setParameters($parameters)->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
        }

        if (!$count instanceof TaxonomyCount) {
            $count = new TaxonomyCount($taxonomy, $date);
            $this->getEntityManager()->persist($count);
        }

        return $count;
    }

    /**
     * @param Category|Tag $taxonomy
     * @param DateTime     $from
     * @param DateTime     $to
     *
     * @return int
     */
    public function countBetween($taxonomy, DateTime $from, DateTime $to)
    {
        $builder = $this->createQueryBuilder('taxonomy_count');
        $builder
            ->select('SUM(taxonomy_count.count)')
            ->where('taxonomy_count.date >= :from')
            ->andWhere('taxonomy_count.date <= :to')
        ;
        $parameters['from'] = $from->format('Y-m-d');
        $parameters['to'] = $to->format('Y-m-d');

        $parameters = [];

        if ($taxonomy instanceof Category) {
            $builder->andWhere('taxonomy_count.category = :category');
            $parameters['category'] = $taxonomy;
        } elseif ($taxonomy instanceof Tag) {
            $builder->andWhere('taxonomy_count.tag = :tag');
            $parameters['tag'] = $taxonomy;
        }

        return intval($builder->setParameters($parameters)->getQuery()->getSingleScalarResult());
    }

    /**
     * @param TaxonomyCount $count
     * @param int           $value
     */
    private function increment(TaxonomyCount $count, $value)
    {
        $count->increment($value);
        $this->getEntityManager()->flush($count);
    }
}
