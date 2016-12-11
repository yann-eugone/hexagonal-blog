<?php

namespace Acme\Domain\Blog\Repository;

use Acme\Domain\Blog\Model\Tag;
use DateTime;

interface TagPostCounterRepository
{
    /**
     * @param Tag $tag
     * @param int $incr
     */
    public function incrementCount(Tag $tag, $incr = 1);

    /**
     * @param Tag      $tag
     * @param DateTime $day
     * @param int      $incr
     */
    public function incrementCountThatDay(Tag $tag, DateTime $day, $incr = 1);

    /**
     * @param Tag $tag
     *
     * @return int
     */
    public function count(Tag $tag);

    /**
     * @param Tag      $tag
     * @param DateTime $day
     *
     * @return int
     */
    public function countThatDay(Tag $tag, DateTime $day);

    /**
     * @param Tag      $tag
     * @param DateTime $from
     * @param DateTime $to
     *
     * @return int
     */
    public function countBetween(Tag $tag, DateTime $from, DateTime $to);
}
