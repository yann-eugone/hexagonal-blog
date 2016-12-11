<?php

namespace Acme\Domain\Blog\Model;

interface Tag
{
    /**
     * @return string
     */
    public function __toString();

    /**
     * @return int
     */
    public function getId();
}
