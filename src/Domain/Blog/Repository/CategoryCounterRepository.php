<?php

namespace Acme\Domain\Blog\Repository;

use Acme\Domain\Blog\Model\Category;
use DateTime;

interface CategoryCounterRepository
{
    /**
     * @param Category $category
     * @param int      $incr
     */
    public function incrementCategoryCount(Category $category, $incr = 1);

    /**
     * @param Category $category
     * @param DateTime $day
     * @param int      $incr
     */
    public function incrementCategoryCountThatDay(Category $category, DateTime $day, $incr = 1);

    /**
     * @param Category $category
     * @param DateTime $day
     *
     * @return int
     */
    public function countCategoryThatDay(Category $category, DateTime $day);

    /**
     * @param Category $category
     * @param DateTime $from
     * @param DateTime $to
     *
     * @return int
     */
    public function countCategoryBetween(Category $category, DateTime $from, DateTime $to);
}
