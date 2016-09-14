<?php

namespace Acme\Infrastructure\Bundle\BlogBundle\Entity\Repository;

use Acme\Domain\Blog\Exception\Comment\CannotCreateCommentException;
use Acme\Domain\Blog\Exception\Comment\CannotDeleteCommentException;
use Acme\Domain\Blog\Exception\Comment\CannotUpdateCommentException;
use Acme\Domain\Blog\Exception\Comment\CommentNotFoundException;
use Acme\Domain\Blog\Model\Comment;
use Acme\Domain\Blog\Model\Post;
use Acme\Domain\Blog\Repository\CommentRepository;
use Acme\Infrastructure\Bundle\BlogBundle\Entity\CommentEntity;
use Doctrine\ORM\EntityRepository;
use Exception;

class CommentEntityRepository extends EntityRepository implements CommentRepository
{
    /**
     * @inheritDoc
     */
    public function instance()
    {
        return new CommentEntity();
    }

    /**
     * @inheritDoc
     */
    public function list(Post $post, array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $criteria['post'] = $post;

        return $this->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @inheritDoc
     */
    public function getById($id)
    {
        if (!$post = $this->find($id)) {
            throw CommentNotFoundException::byId($id);
        }

        return $post;
    }

    /**
     * @inheritDoc
     */
    public function create(Comment $comment)
    {
        try {
            $this->getEntityManager()->persist($comment);
            $this->getEntityManager()->flush($comment);
        } catch (Exception $exception) {
            throw CannotCreateCommentException::onException($exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function update(Comment $comment)
    {
        try {
            $this->getEntityManager()->flush($comment);
        } catch (Exception $exception) {
            throw CannotUpdateCommentException::onException($exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function delete(Comment $comment)
    {
        try {
            $this->getEntityManager()->remove($comment);
            $this->getEntityManager()->flush($comment);
        } catch (Exception $exception) {
            throw CannotDeleteCommentException::onException($exception);
        }
    }
}
