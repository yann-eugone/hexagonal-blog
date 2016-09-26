<?php

namespace Acme\Application\Blog\Event\Post;

use DateTime;

class PostDeleted
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var DateTime
     */
    private $date;

    /**
     * @param int      $id
     * @param DateTime $date
     */
    public function __construct($id, DateTime $date)
    {
        $this->id = $id;
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}
