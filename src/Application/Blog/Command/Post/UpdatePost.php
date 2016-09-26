<?php

namespace Acme\Application\Blog\Command\Post;

use Acme\Domain\Blog\Model\Category;
use Acme\Domain\Blog\Model\Tag;

class UpdatePost
{
    /**
     * @var int
     */
    private $id;

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
     * @var Category
     */
    private $category;

    /**
     * @var Tag[]
     */
    private $tags;

    /**
     * @param int      $id
     * @param string   $title
     * @param string   $summary
     * @param string   $body
     * @param Category $category
     * @param Tag[]    $tags
     */
    public function __construct($id, $title, $summary, $body, Category $category, $tags)
    {
        $this->id = $id;
        $this->title = $title;
        $this->summary = $summary;
        $this->body = $body;
        $this->category = $category;
        $this->tags = $tags;
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
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return Tag[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param Tag[] $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }
}
