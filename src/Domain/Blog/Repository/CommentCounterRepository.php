<?php

namespace Acme\Domain\Blog\Repository;

use Acme\Domain\Blog\Model\Author;
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
     * @param Author $author
     * @param int    $incr
     */
    public function incrementCountForAuthor(Author $author, $incr = 1);

    /**
     * @param Author   $author
     * @param DateTime $day
     * @param int      $incr
     */
    public function incrementCountForAuthorThatDay(Author $author, DateTime $day, $incr = 1);

    /**
     * @return int
     */
    public function count();

    /**
     * @param Author $author
     *
     * @return int
     */
    public function countForAuthor(Author $author);

    /**
     * @param DateTime $day
     *
     * @return int
     */
    public function countThatDay(DateTime $day);

    /**
     * @param DateTime $day
     * @param Author   $author
     *
     * @return int
     */
    public function countThatDayForAuthor(DateTime $day, Author $author);

    /**
     * @param DateTime $from
     * @param DateTime $to
     *
     * @return int
     */
    public function countBetween(DateTime $from, DateTime $to);

    /**
     * @param DateTime $from
     * @param DateTime $to
     * @param Author   $author
     *
     * @return int
     */
    public function countBetweenForAuthor(DateTime $from, DateTime $to, Author $author);
}
