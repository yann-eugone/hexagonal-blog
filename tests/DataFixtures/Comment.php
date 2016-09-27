<?php

namespace Acme\Tests\DataFixtures;

class Comment implements \Acme\Domain\Blog\Model\Comment
{
    public $id;
    public $comment;
    public $postedAt;
    public $author;
    public $post;

    /**
     * @param $id
     * @param $comment
     * @param $postedAt
     * @param $author
     * @param $post
     */
    public function __construct($id = null, $comment = null, $postedAt = null, $author = null, $post = null)
    {
        $this->id = $id;
        $this->comment = $comment;
        $this->postedAt = $postedAt;
        $this->author = $author;
        $this->post = $post;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    public function getPostedAt()
    {
        return $this->postedAt;
    }

    public function setPostedAt($postedAt)
    {
        $this->postedAt = $postedAt;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function getPost()
    {
        return $this->post;
    }

    public function setPost($post)
    {
        $this->post = $post;
    }
}
