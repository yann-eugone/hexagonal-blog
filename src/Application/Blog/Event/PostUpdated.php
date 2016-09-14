<?php

namespace Acme\Application\Blog\Event;

class PostUpdated
{
    /**
     * @var int
     */
    private $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
