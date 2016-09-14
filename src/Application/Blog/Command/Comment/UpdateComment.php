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
     *
     * @return UpdateComment
     */
    public static function fromComment(Comment $comment)
    {
        return new self(
            $comment->getId(),
            $comment->getComment()
        );
    }

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
}
