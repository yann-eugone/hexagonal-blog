<?php

namespace Acme\Domain\Blog\Model;

interface Author
{
    /**
     * @return string
     */
    public function __toString();

    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getUsername();
}
