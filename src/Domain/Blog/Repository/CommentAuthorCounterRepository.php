<?php

namespace Acme\Domain\Blog\Repository;

use Acme\Domain\Blog\Model\Author;
use DateTime;

interface CommentAuthorCounterRepository
{
    /**
     * @param Author $author
     * @param int    $incr
     */
    public function incrementCount(Author $author, $incr = 1);

    /**
     * @param Author   $author
     * @param DateTime $day
     * @param int      $incr
     */
    public function incrementCountThatDay(Author $author, DateTime $day, $incr = 1);

    /**
     * @param Author $author
     *
     * @return int
     */
    public function count(Author $author);

    /**
     * @param DateTime $day
     * @param Author   $author
     *
     * @return int
     */
    public function countThatDay(DateTime $day, Author $author);

    /**
     * @param DateTime $from
     * @param DateTime $to
     * @param Author   $author
     *
     * @return int
     */
    public function countBetween(DateTime $from, DateTime $to, Author $author);
}
