<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Entity;

use Acme\Domain\Blog\Model\Post as PostInterface;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 * @ORM\Table(name="post")
 */
class Post implements PostInterface
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
     *
     * @Groups({"event_bus"})
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"event_bus"})
     */
    private $summary;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     *
     * @Groups({"event_bus"})
     */
    private $body;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetimetz")
     *
     * @Groups({"event_bus"})
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
     * @ORM\JoinTable(name="post_tag")
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
     * @return mixed
     *
     * @Groups({"event_bus"})
     */
    public function getCategoryId()
    {
        return $this->category->getId();
    }

    /**
     * @return array
     *
     * @Groups({"event_bus"})
     */
    public function getTagIds()
    {
        return $this->tags->map(function (Tag $tag) { return $tag->getId(); })->toArray();
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
