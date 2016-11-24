<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM;

use Acme\Domain\Blog\Exception\Comment\CannotCreateCommentException;
use Acme\Domain\Blog\Exception\Comment\CannotDeleteCommentException;
use Acme\Domain\Blog\Exception\Comment\CannotUpdateCommentException;
use Acme\Domain\Blog\Exception\Comment\CommentNotFoundException;
use Acme\Domain\Blog\Model\Comment;
use Acme\Domain\Blog\Model\Post;
use Acme\Domain\Blog\Repository\CommentRepository as CommentRepositoryInterface;
use Acme\Infrastructure\Bundle\AppBundle\Entity\Comment as CommentEntity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;

class CommentRepository implements CommentRepositoryInterface
{
    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @param EntityManager    $manager
     * @param EntityRepository $repository
     */
    public function __construct(EntityManager $manager, EntityRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

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
    public function search(Post $post, array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $criteria['post'] = $post;

        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @inheritDoc
     */
    public function getById($id)
    {
        if (!$comment = $this->repository->find($id)) {
            throw CommentNotFoundException::byId($id);
        }

        return $comment;
    }

    /**
     * @inheritDoc
     */
    public function create(Comment $comment)
    {
        try {
            $this->manager->persist($comment);
            $this->manager->flush($comment);
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
            $this->manager->flush($comment);
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
            $this->manager->remove($comment);
            $this->manager->flush($comment);
        } catch (Exception $exception) {
            throw CannotDeleteCommentException::onException($exception);
        }
    }
}
