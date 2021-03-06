<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Entity;

use Acme\Domain\Blog\Model\Comment as CommentInterface;
use Acme\Domain\Blog\Model\Post as PostInterface;
use Acme\Domain\Blog\Model\Author as AuthorInterface;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 * @ORM\Table(name="post_comment")
 */
class Comment implements CommentInterface
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
    private $comment;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetimetz")
     *
     * @Groups({"event_bus"})
     */
    private $postedAt;

    /**
     * @var AuthorInterface
     *
     * @ORM\ManyToOne(targetEntity="Acme\Domain\Blog\Model\Author")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $author;

    /**
     * @var PostInterface
     *
     * @ORM\ManyToOne(targetEntity="Acme\Domain\Blog\Model\Post")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $post;

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
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @inheritdoc
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
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
     * @inheritdoc
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @inheritdoc
     */
    public function setPost($post)
    {
        $this->post = $post;
    }

    /**
     * @return int
     *
     * @Groups({"event_bus"})
     */
    public function getAuthorId()
    {
        return $this->author->getId();
    }

    /**
     * @return int
     *
     * @Groups({"event_bus"})
     */
    public function getPostId()
    {
        return $this->post->getId();
    }
}
