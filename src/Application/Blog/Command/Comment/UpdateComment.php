<?php

namespace Acme\Application\Blog\Command\Comment;

class UpdateComment
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $text;

    /**
     * @param int    $id
     * @param string $text
     */
    public function __construct($id, $text)
    {
        $this->id = $id;
        $this->text = $text;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }
}
