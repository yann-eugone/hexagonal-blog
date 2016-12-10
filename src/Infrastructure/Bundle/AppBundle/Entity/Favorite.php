<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="favorite", uniqueConstraints={@ORM\UniqueConstraint(columns={"author_id", "post_id"})})
 */
class Favorite
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
     * @var Author
     *
     * @ORM\OneToMany(targetEntity="Acme\Infrastructure\Bundle\AppBundle\Entity\Author")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $author;

    /**
     * @var Post
     *
     * @ORM\OneToMany(targetEntity="Acme\Infrastructure\Bundle\AppBundle\Entity\Post")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $post;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $favoritedAt;

    /**
     * @param Author   $author
     * @param Post     $post
     * @param DateTime $favoritedAt
     */
    public function __construct(Author $author, Post $post, DateTime $favoritedAt = null)
    {
        $this->author = $author;
        $this->post = $post;
        $this->favoritedAt = $favoritedAt ?: new DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @return DateTime
     */
    public function getFavoritedAt()
    {
        return $this->favoritedAt;
    }
}
