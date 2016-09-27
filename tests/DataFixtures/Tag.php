<?php

namespace Acme\Tests\DataFixtures;

class Tag implements \Acme\Domain\Blog\Model\Tag
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
