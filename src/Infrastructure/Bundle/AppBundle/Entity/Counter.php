<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Entity;

use Acme\Domain\Blog\Model\Post;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Acme\Infrastructure\Bundle\AppBundle\Entity\Repository\CounterRepository")
 * @ORM\Table(name="counter")
 */
class Counter
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
     * @ORM\Column(type="string", length=50)
     */
    private $type;

    /**
     * @var Tag|null
     *
     * @ORM\ManyToOne(targetEntity="Acme\Domain\Blog\Model\Tag")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $tag;

    /**
     * @var Category|null
     *
     * @ORM\ManyToOne(targetEntity="Acme\Domain\Blog\Model\Category")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $category;

    /**
     * @var Post|null
     *
     * @ORM\ManyToOne(targetEntity="Acme\Domain\Blog\Model\Post")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $post;

    /**
     * @var Author|null
     *
     * @ORM\ManyToOne(targetEntity="Acme\Domain\Blog\Model\Author")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $author;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @param string        $type
     * @param DateTime|null $date
     * @param object|null   $subject
     * @param Author|null   $author
     */
    public function __construct(
        $type,
        DateTime $date = null,
        $subject = null,
        Author $author = null
    ) {
        $this->type = $type;
        $this->date = $date;
        $this->author = $author;

        if ($subject instanceof Category) {
            $this->category = $subject;
        } elseif ($subject instanceof Tag) {
            $this->tag = $subject;
        } elseif ($subject instanceof Post) {
            $this->post = $subject;
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
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param int $value
     */
    public function increment($value = 1)
    {
        $this->value += $value;
    }
}
