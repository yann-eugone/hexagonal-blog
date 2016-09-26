<?php

namespace Acme\Application\Blog\Event\Post;

use DateTime;

class PostCreated
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
     * @var array
     */
    private $data;

    /**
     * @param int      $id
     * @param DateTime $date
     * @param array    $data
     */
    public function __construct($id, DateTime $date, array $data)
    {
        $this->id = $id;
        $this->date = $date;
        $this->data = $data;
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

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
