<?php

namespace Acme\Domain\Blog\Repository;

use Acme\Domain\Blog\Exception\Comment\CannotCreateCommentException;
use Acme\Domain\Blog\Exception\Comment\CannotDeleteCommentException;
use Acme\Domain\Blog\Exception\Comment\CannotUpdateCommentException;
use Acme\Domain\Blog\Exception\Comment\CommentNotFoundException;
use Acme\Domain\Blog\Model\Comment;
use Acme\Domain\Blog\Model\Post;

interface CommentRepository
{
    /**
     * @return Comment
     */
    public function instance();

    /**
     * @param Post  $post
     * @param array $criteria
     * @param array $orderBy
     * @param null  $limit
     * @param null  $offset
     *
     * @return Comment[]
     */
    public function search(Post $post, array $criteria, array $orderBy = null, $limit = null, $offset = null);

    /**
     * @param int $id
     *
     * @return Comment
     * @throws CommentNotFoundException
     */
    public function getById($id);

    /**
     * @param Comment $comment
     *
     * @throws CannotCreateCommentException
     */
    public function create(Comment $comment);

    /**
     * @param Comment $comment
     *
     * @throws CannotUpdateCommentException
     */
    public function update(Comment $comment);

    /**
     * @param Comment $comment
     *
     * @throws CannotDeleteCommentException
     */
    public function delete(Comment $comment);
}
