<?php

namespace Acme\Infrastructure\Bundle\BlogBundle\Entity\Repository;

use Acme\Domain\Blog\Exception\CannotCreatePostException;
use Acme\Domain\Blog\Exception\CannotDeletePostException;
use Acme\Domain\Blog\Exception\CannotUpdatePostException;
use Acme\Domain\Blog\Exception\PostNotFoundException;
use Acme\Domain\Blog\Model\Post;
use Acme\Domain\Blog\Repository\PostRepository as PostRepositoryInterface;
use Acme\Infrastructure\Bundle\BlogBundle\Entity\PostEntity;
use Doctrine\ORM\EntityRepository;
use Exception;

class PostEntityRepository extends EntityRepository implements PostRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function instance()
    {
        return new PostEntity();
    }

    /**
     * @inheritdoc
     */
    public function list(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @inheritDoc
     */
    public function getById($id)
    {
        if (!$post = $this->find($id)) {
            throw PostNotFoundException::byId($id);
        }

        return $post;
    }

    /**
     * @inheritDoc
     */
    public function create(Post $post)
    {
        try {
            $this->getEntityManager()->persist($post);
            $this->getEntityManager()->flush($post);
        } catch (Exception $exception) {
            throw CannotCreatePostException::onException($exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function update(Post $post)
    {
        try {
            $this->getEntityManager()->flush($post);
        } catch (Exception $exception) {
            throw CannotUpdatePostException::onException($exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function delete(Post $post)
    {
        try {
            $this->getEntityManager()->remove($post);
            $this->getEntityManager()->flush($post);
        } catch (Exception $exception) {
            throw CannotDeletePostException::onException($exception);
        }
    }
}
