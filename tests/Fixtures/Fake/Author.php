<?php

namespace Acme\Tests\Fixtures\Fake;

class Author implements \Acme\Domain\Blog\Model\Author
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
