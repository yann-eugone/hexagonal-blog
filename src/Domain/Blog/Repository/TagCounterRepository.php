<?php

namespace Acme\Domain\Blog\Repository;

use Acme\Domain\Blog\Model\Tag;
use DateTime;

interface TagCounterRepository
{
    /**
     * @param Tag $tag
     * @param int $incr
     */
    public function incrementTagCount(Tag $tag, $incr = 1);

    /**
     * @param Tag      $tag
     * @param DateTime $day
     * @param int      $incr
     */
    public function incrementTagCountThatDay(Tag $tag, DateTime $day, $incr = 1);

    /**
     * @param Tag      $tag
     * @param DateTime $day
     *
     * @return int
     */
    public function countTagThatDay(Tag $tag, DateTime $day);

    /**
     * @param Tag      $tag
     * @param DateTime $from
     * @param DateTime $to
     *
     * @return int
     */
    public function countTagBetween(Tag $tag, DateTime $from, DateTime $to);
}
