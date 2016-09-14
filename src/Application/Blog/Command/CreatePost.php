<?php

namespace Acme\Application\Blog\Command;

use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Model\Post;

class CreatePost
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $summary;

    /**
     * @var string
     */
    private $body;

    /**
     * @var Author
     */
    private $author;

    /**
     * @var Post|null
     */
    private $post;

    /**
     * @param string $title
     * @param string $summary
     * @param string $body
     * @param Author $author
     */
    public function __construct($title, $summary, $body, Author $author)
    {
        $this->title = $title;
        $this->summary = $summary;
        $this->body = $body;
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return Post|null
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param Post|null $post
     */
    public function setPost($post)
    {
        $this->post = $post;
    }
}
