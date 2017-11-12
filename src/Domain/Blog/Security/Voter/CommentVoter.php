<?php

namespace Acme\Domain\Blog\Security\Voter;

use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Model\Comment;

class CommentVoter
{
    /**
     * @param Comment     $comment
     * @param Author|null $author
     *
     * @return bool
     */
    public function allowedToReply(Comment $comment, Author $author = null)
    {
        return null !== $author; //todo enough ?
    }

    /**
     * @param Comment     $comment
     * @param Author|null $author
     *
     * @return bool
     */
    public function allowedToUpdate(Comment $comment, Author $author = null)
    {
        return null !== $author
               && $author === $comment->getAuthor();
    }

    /**
     * @param Comment     $comment
     * @param Author|null $author
     *
     * @return bool
     */
    public function allowedToDelete(Comment $comment, Author $author = null)
    {
        return null !== $author
               && $author === $comment->getAuthor();
    }
}
