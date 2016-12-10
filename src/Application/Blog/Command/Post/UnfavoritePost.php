<?php

namespace Acme\Application\Blog\Command\Post;

use DateTime;

class UnfavoritePost
{
    /**
     * @var int
     */
    private $postId;

    /**
     * @var int
     */
    private $authorId;

    /**
     * @var DateTime
     */
    private $date;

    /**
     * @param int      $postId
     * @param int      $authorId
     * @param DateTime $date
     */
    public function __construct($postId, $authorId, DateTime $date)
    {
        $this->postId = $postId;
        $this->authorId = $authorId;
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * @return int
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}
