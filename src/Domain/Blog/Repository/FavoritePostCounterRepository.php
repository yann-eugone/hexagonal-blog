<?php

namespace Acme\Domain\Blog\Repository;

use Acme\Domain\Blog\Model\Post;
use DateTime;

interface FavoritePostCounterRepository
{
    /**
     * @param Post $post
     * @param int  $incr
     *
     * @return int
     */
    public function incrementCount(Post $post, $incr = 1);

    /**
     * @param Post     $post
     * @param DateTime $day
     * @param int      $incr
     *
     * @return int
     */
    public function incrementCountThatDay(Post $post, DateTime $day, $incr = 1);

    /**
     * @param Post $post
     *
     * @return int
     */
    public function count(Post $post);

    /**
     * @param Post     $post
     * @param DateTime $day
     *
     * @return int
     */
    public function countThatDay(Post $post, DateTime $day);

    /**
     * @param Post     $post
     * @param DateTime $from
     * @param DateTime $to
     *
     * @return int
     */
    public function countBetween(Post $post, DateTime $from, DateTime $to);
}
