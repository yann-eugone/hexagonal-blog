<?php

namespace Acme\Tests\DataFixtures;

class Post implements \Acme\Domain\Blog\Model\Post
{
    public $id;
    public $title;
    public $summary;
    public $body;
    public $postedAt;
    public $author;
    public $category;
    public $tags;

    /**
     * @param $id
     * @param $title
     * @param $summary
     * @param $body
     * @param $postedAt
     * @param $author
     * @param $category
     * @param $tags
     */
    public function __construct(
        $id = null,
        $title = null,
        $summary = null,
        $body = null,
        $postedAt = null,
        $author = null,
        $category = null,
        $tags = []
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->summary = $summary;
        $this->body = $body;
        $this->postedAt = $postedAt;
        $this->author = $author;
        $this->category = $category;
        $this->tags = $tags;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getSummary()
    {
        return $this->summary;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getPostedAt()
    {
        return $this->postedAt;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function setPostedAt($postedAt)
    {
        $this->postedAt = $postedAt;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
    }
}
