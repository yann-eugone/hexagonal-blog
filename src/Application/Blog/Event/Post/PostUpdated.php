<?php

namespace Acme\Application\Blog\Event\Post;

use DateTime;

class PostUpdated
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
    private $dataBefore;

    /**
     * @var array
     */
    private $dataAfter;

    /**
     * @param int      $id
     * @param DateTime $date
     * @param array    $dataBefore
     * @param array    $dataAfter
     */
    public function __construct($id, DateTime $date, array $dataBefore, array $dataAfter)
    {
        $this->id = $id;
        $this->date = $date;
        $this->dataBefore = $dataBefore;
        $this->dataAfter = $dataAfter;
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
    public function getDataBefore()
    {
        return $this->dataBefore;
    }

    /**
     * @return array
     */
    public function getDataAfter()
    {
        return $this->dataAfter;
    }
}
