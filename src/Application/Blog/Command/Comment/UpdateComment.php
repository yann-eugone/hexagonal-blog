<?php

namespace Acme\Application\Blog\Command\Comment;

use Acme\Domain\Blog\Model\Comment;

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
     * @param Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->id = $comment->getId();
        $this->text = $comment->getComment();
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
