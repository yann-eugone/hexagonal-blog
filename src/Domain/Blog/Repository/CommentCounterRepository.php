<?php

namespace Acme\Domain\Blog\Repository;

use DateTime;

interface CommentCounterRepository
{
    /**
     * @param int $incr
     */
    public function incrementCount($incr = 1);

    /**
     * @param DateTime $day
     * @param int      $incr
     */
    public function incrementCountThatDay(DateTime $day, $incr = 1);

    /**
     * @return int
     */
    public function count();

    /**
     * @param DateTime $day
     *
     * @return int
     */
    public function countThatDay(DateTime $day);

    /**
     * @param DateTime $from
     * @param DateTime $to
     *
     * @return int
     */
    public function countBetween(DateTime $from, DateTime $to);
}
