<?php

namespace Acme\Tests\Fixtures\Fake;

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

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->name;
    }
}
