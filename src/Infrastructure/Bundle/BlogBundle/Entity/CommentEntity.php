<?php

namespace Acme\Infrastructure\Bundle\BlogBundle\Entity;

use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Model\Comment;
use Acme\Domain\Blog\Model\Post;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="Acme\Infrastructure\Bundle\BlogBundle\Entity\Repository\CommentEntityRepository")
 * @ORM\Table(name="blog_post_comment")
 */
class CommentEntity implements Comment
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
     * @var Author
     *
     * @ORM\ManyToOne(targetEntity="Acme\Domain\Blog\Model\Author")
     */
    private $author;

    /**
     * @var Post
     *
     * @ORM\ManyToOne(targetEntity="Acme\Domain\Blog\Model\Post")
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
     * @return mixed
     *
     * @Groups({"event_bus"})
     */
    public function getPostId()
    {
        return $this->post->getId();
    }
}
