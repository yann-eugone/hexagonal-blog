<?php

namespace Acme\Domain\Blog\Repository;

use Acme\Domain\Blog\Model\Author;
use DateTime;

interface AuthorFavoriteCounterRepository
{
    /**
     * @param Author $author
     * @param int    $incr
     *
     * @return int
     */
    public function incrementCount(Author $author, $incr = 1);

    /**
     * @param Author   $author
     * @param DateTime $day
     * @param int      $incr
     *
     * @return int
     */
    public function incrementCountThatDay(Author $author, DateTime $day, $incr = 1);

    /**
     * @param Author $author
     *
     * @return int
     */
    public function count(Author $author);

    /**
     * @param Author   $author
     * @param DateTime $day
     *
     * @return int
     */
    public function countThatDay(Author $author, DateTime $day);

    /**
     * @param Author   $author
     * @param DateTime $from
     * @param DateTime $to
     *
     * @return int
     */
    public function countBetween(Author $author, DateTime $from, DateTime $to);
}
