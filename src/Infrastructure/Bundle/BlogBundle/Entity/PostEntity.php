<?php

namespace Acme\Infrastructure\Bundle\BlogBundle\Entity;

use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Model\Category;
use Acme\Domain\Blog\Model\Post as PostInterface;
use Acme\Domain\Blog\Model\Tag;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Acme\Infrastructure\Bundle\BlogBundle\Entity\Repository\PostEntityRepository")
 * @ORM\Table(name="blog_post")
 */
class PostEntity implements PostInterface
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $summary;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $body;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetimetz")
     */
    private $postedAt;

    /**
     * @var Author
     *
     * @ORM\ManyToOne(targetEntity="Acme\Domain\Blog\Model\Author")
     */
    private $author;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Acme\Domain\Blog\Model\Category")
     */
    private $category;

    /**
     * @var Tag[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="Acme\Domain\Blog\Model\Tag")
     * @ORM\JoinTable(name="blog_post_tag")
     */
    private $tags;

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->title;
    }

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @inheritdoc
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @inheritdoc
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @inheritdoc
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    /**
     * @inheritdoc
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @inheritdoc
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @inheritdoc
     */
    public function getPostedAt()
    {
        return $this->postedAt;
    }

    /**
     * @inheritdoc
     */
    public function setPostedAt($postedAt)
    {
        $this->postedAt = $postedAt;
    }

    /**
     * @inheritdoc
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @inheritdoc
     */
    public function setAuthor($author)
    {
        $this->author = $author;
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
     * @inheritdoc
     */
    public function getTags()
    {
        return $this->tags->toArray();
    }

    /**
     * @inheritdoc
     */
    public function setTags($tags)
    {
        $this->tags->clear();
        foreach ($tags as $tag) {
            $this->tags->add($tag);
        }
    }

    /**
     * @inheritDoc
     */
    public function __clone()
    {
        $tags = $this->getTags();

        $this->tags = new ArrayCollection();
        foreach ($tags as $tag) {
            $this->tags->add($tag);
        }
    }
}
