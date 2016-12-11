<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Entity;

use Acme\Domain\Blog\Model\Author as AuthorInterface;
use Acme\Domain\Blog\Model\Post as PostInterface;
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
     * @var AuthorInterface
     *
     * @ORM\OneToMany(targetEntity="Acme\Domain\Blog\Model\Author")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $author;

    /**
     * @var PostInterface
     *
     * @ORM\OneToMany(targetEntity="Acme\Domain\Blog\Model\Post")
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
     * @param AuthorInterface $author
     * @param PostInterface   $post
     * @param DateTime        $favoritedAt
     */
    public function __construct(AuthorInterface $author, PostInterface $post, DateTime $favoritedAt = null)
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
     * @return AuthorInterface
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return PostInterface
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
