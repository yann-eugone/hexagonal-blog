<?php

namespace Acme\Application\Blog\Command\Comment;

use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Model\Comment;
use Acme\Domain\Blog\Model\Post;

class CreateComment
{
    /**
     * @var string
     */
    private $text;

    /**
     * @var Author
     */
    private $author;

    /**
     * @var Post
     */
    private $post;

    /**
     * @var Comment|null
     */
    private $comment;

    /**
     * @param Author $author
     * @param Post   $post
     */
    public function __construct(Author $author, Post $post)
    {
        $this->author = $author;
        $this->post = $post;
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

    /**
     * @return Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @return Comment|null
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param Comment|null $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }
}
