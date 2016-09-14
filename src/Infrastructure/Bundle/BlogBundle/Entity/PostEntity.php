<?php

namespace Acme\Infrastructure\Bundle\BlogBundle\Entity;

use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Model\Post as PostInterface;
use DateTime;
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
     * @var DateTime|null
     *
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $publishedAt;

    /**
     * @var Author
     *
     * @ORM\ManyToOne(targetEntity="Acme\Domain\Blog\Model\Author")
     */
    private $author;

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
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * @inheritdoc
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;
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
}
