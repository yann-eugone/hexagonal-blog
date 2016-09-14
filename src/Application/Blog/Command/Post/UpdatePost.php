<?php

namespace Acme\Application\Blog\Command\Post;

use Acme\Domain\Blog\Model\Post;

class UpdatePost
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $summary;

    /**
     * @var string
     */
    private $body;

    /**
     * @param Post $post
     *
     * @return UpdatePost
     */
    public static function fromPost(Post $post)
    {
        return new self(
            $post->getId(),
            $post->getTitle(),
            $post->getSummary(),
            $post->getBody()
        );
    }

    /**
     * @param int    $id
     * @param string $title
     * @param string $summary
     * @param string $body
     */
    public function __construct($id, $title, $summary, $body)
    {
        $this->id = $id;
        $this->title = $title;
        $this->summary = $summary;
        $this->body = $body;
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
}
