<?php

namespace Acme\Domain\Blog\Model;

interface Category
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
