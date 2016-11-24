<?php

namespace Acme\Domain\Blog\Repository;

use Acme\Domain\Blog\Model\Category;
use Acme\Domain\Blog\Model\Tag;
use DateTime;

interface TaxonomyCounterRepository
{
    /**
     * @param Category $category
     * @param int      $incr
     */
    public function incrementPostWithCategoryCount(Category $category, $incr = 1);

    /**
     * @param Category $category
     * @param DateTime $day
     * @param int      $incr
     */
    public function incrementPostWithCategoryCountThatDay(Category $category, DateTime $day, $incr = 1);

    /**
     * @param Category $category
     * @param DateTime $day
     *
     * @return int
     */
    public function countPostWithCategoryThatDay(Category $category, DateTime $day);

    /**
     * @param Category $category
     * @param DateTime $from
     * @param DateTime $to
     *
     * @return int
     */
    public function countPostWithCategoryBetween(Category $category, DateTime $from, DateTime $to);

    /**
     * @param Tag $tag
     * @param int $incr
     */
    public function incrementPostWithTagCount(Tag $tag, $incr = 1);

    /**
     * @param Tag      $tag
     * @param DateTime $day
     * @param int      $incr
     */
    public function incrementPostWithTagCountThatDay(Tag $tag, DateTime $day, $incr = 1);

    /**
     * @param Tag      $tag
     * @param DateTime $day
     *
     * @return int
     */
    public function countPostWithTagThatDay(Tag $tag, DateTime $day);

    /**
     * @param Tag      $tag
     * @param DateTime $from
     * @param DateTime $to
     *
     * @return int
     */
    public function countPostWithTagBetween(Tag $tag, DateTime $from, DateTime $to);
}
