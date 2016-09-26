<?php

namespace Acme\Domain\Blog\Model;

use DateTime;

interface Post
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $title
     */
    public function setTitle($title);

    /**
     * @return string
     */
    public function getSummary();

    /**
     * @param string $summary
     */
    public function setSummary($summary);

    /**
     * @return string
     */
    public function getBody();

    /**
     * @param string $body
     */
    public function setBody($body);

    /**
     * @return Category
     */
    public function getCategory();

    /**
     * @param Category $category
     */
    public function setCategory($category);

    /**
     * @return Tag[]
     */
    public function getTags();

    /**
     * @param Tag[] $tags
     */
    public function setTags($tags);

    /**
     * @return DateTime|null
     */
    public function getPostedAt();

    /**
     * @param DateTime $postedAt
     */
    public function setPostedAt($postedAt);

    /**
     * @return DateTime
     */
    public function getPublishedAt();

    /**
     * @param DateTime $publishedAt
     */
    public function setPublishedAt($publishedAt);

    /**
     * @return Author
     */
    public function getAuthor();

    /**
     * @param Author $author
     */
    public function setAuthor($author);
}
