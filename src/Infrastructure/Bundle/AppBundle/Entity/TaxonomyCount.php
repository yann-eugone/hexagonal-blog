<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Entity;

use Acme\Domain\Blog\Model\Category;
use Acme\Domain\Blog\Model\Tag;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Acme\Infrastructure\Bundle\AppBundle\Entity\Repository\TaxonomyCountRepository")
 * @ORM\Table(name="taxonomy_count")
 */
class TaxonomyCount
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
     * @var DateTime|null
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @var Tag|null
     *
     * @ORM\ManyToOne(targetEntity="Acme\Domain\Blog\Model\Tag")
     */
    private $tag;

    /**
     * @var Category|null
     *
     * @ORM\ManyToOne(targetEntity="Acme\Domain\Blog\Model\Category")
     */
    private $category;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @param Category|Tag  $taxonomy
     * @param DateTime|null $date
     */
    public function __construct($taxonomy, $date = null)
    {
        $this->date = $date;
        $this->value = 0;

        if ($taxonomy instanceof Category) {
            $this->category = $taxonomy;
        } elseif ($taxonomy instanceof Tag) {
            $this->tag = $taxonomy;
        } else {
            throw new \RuntimeException('Unexpected taxonomy : ' . get_class($taxonomy));
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
     * @return DateTime|null
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return Tag|null
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @return Category|null
     */
    public function getCategory()
    {
        return $this->category;
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
