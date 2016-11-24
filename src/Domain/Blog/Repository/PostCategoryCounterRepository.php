<?php

namespace Acme\Domain\Blog\Repository;

use Acme\Domain\Blog\Model\Category;
use DateTime;

interface PostCategoryCounterRepository
{
    /**
     * @param Category $category
     * @param int      $incr
     */
    public function incrementCount(Category $category, $incr = 1);

    /**
     * @param Category $category
     * @param DateTime $day
     * @param int      $incr
     */
    public function incrementCountThatDay(Category $category, DateTime $day, $incr = 1);

    /**
     * @param Category $category
     *
     * @return int
     */
    public function count(Category $category);

    /**
     * @param Category $category
     * @param DateTime $day
     *
     * @return int
     */
    public function countThatDay(Category $category, DateTime $day);

    /**
     * @param Category $category
     * @param DateTime $from
     * @param DateTime $to
     *
     * @return int
     */
    public function countBetween(Category $category, DateTime $from, DateTime $to);
}
