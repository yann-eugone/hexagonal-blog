<?php

namespace Acme\Application\Blog\Event\Post;

class PostDeleted
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
