<?php

namespace Acme\Application\Blog\Command\Post;

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
     * @param Author $author
     */
    public function __construct(Author $author)
    {
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
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
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
