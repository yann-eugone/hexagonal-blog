<?php

namespace Acme\Tests\DataFixtures;

class Category implements \Acme\Domain\Blog\Model\Category
{
    public $name;

    /**
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return $this->name;
    }
}
