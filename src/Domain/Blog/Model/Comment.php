<?php

namespace Acme\Domain\Blog\Model;

use DateTime;

interface Comment
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getComment();

    /**
     * @param string $comment
     */
    public function setComment($comment);

    /**
     * @return DateTime
     */
    public function getPostedAt();

    /**
     * @param DateTime $postedAt
     */
    public function setPostedAt($postedAt);

    /**
     * @return Author
     */
    public function getAuthor();

    /**
     * @param Author $author
     */
    public function setAuthor($author);

    /**
     * @return Post
     */
    public function getPost();

    /**
     * @param Post $post
     */
    public function setPost($post);
}
