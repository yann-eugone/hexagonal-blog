<?php

namespace Acme\Application\Blog\Command\Comment;

use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Model\Comment;
use Acme\Domain\Blog\Model\Post;

class CommentCommandFactory
{
    /**
     * @param Author $author
     * @param Post   $post
     *
     * @return CreateComment
     */
    public function newCreateCommand(Author $author, Post $post)
    {
        return new CreateComment($author, $post);
    }

    /**
     * @param Comment $comment
     *
     * @return UpdateComment
     */
    public function newUpdateCommand(Comment $comment)
    {
        return new UpdateComment(
            $comment->getId(),
            $comment->getComment()
        );
    }

    /**
     * @param Comment $comment
     *
     * @return DeleteComment
     */
    public function newDeleteCommand(Comment $comment)
    {
        return new DeleteComment($comment->getId());
    }
}
