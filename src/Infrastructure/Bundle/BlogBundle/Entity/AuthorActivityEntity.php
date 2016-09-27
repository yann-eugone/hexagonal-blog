<?php

namespace Acme\Infrastructure\Bundle\BlogBundle\Entity;

use Acme\Domain\Blog\Model\AuthorActivity;
use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Model\Comment;
use Acme\Domain\Blog\Model\Post;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Acme\Infrastructure\Bundle\BlogBundle\Entity\Repository\AuthorActivityEntityRepository")
 * @ORM\Table(name="blog_author_activity")
 */
class AuthorActivityEntity implements AuthorActivity
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
    private $action;

    /**
     * @var Author
     *
     * @ORM\ManyToOne(targetEntity="Acme\Domain\Blog\Model\Author")
     */
    private $author;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @var array
     *
     * @ORM\Column(type="json_array")
     */
    private $payload;

    /**
     * @var Post
     *
     * @ORM\ManyToOne(targetEntity="Acme\Domain\Blog\Model\Post")
     */
    private $post;

    /**
     * @var Comment
     *
     * @ORM\ManyToOne(targetEntity="Acme\Domain\Blog\Model\Comment")
     */
    private $comment;

    /**
     * @param string   $action
     * @param Author   $author
     * @param DateTime $date
     * @param object   $subject
     * @param array    $payload
     */
    public function __construct($action, Author $author, DateTime $date, $subject, array $payload)
    {
        $this->action = $action;
        $this->author = $author;
        $this->date = $date;
        $this->payload = $payload;

        if ($subject instanceof Comment) {
            $this->comment = $subject;
        } elseif ($subject instanceof Post) {
            $this->post = $subject;
        } else {
            throw new \RuntimeException('Unexpecte subject : ' . get_class($subject));
        }
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
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @return object
     */
    public function getSubject()
    {
        if ($this->comment) {
            return $this->comment;
        }

        if ($this->post) {
            return $this->post;
        }

        throw new \RuntimeException('No subject on author activity');
    }
}
