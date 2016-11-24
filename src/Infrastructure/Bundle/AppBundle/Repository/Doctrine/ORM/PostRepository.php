<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM;

use Acme\Domain\Blog\Exception\Post\CannotCreatePostException;
use Acme\Domain\Blog\Exception\Post\CannotDeletePostException;
use Acme\Domain\Blog\Exception\Post\CannotUpdatePostException;
use Acme\Domain\Blog\Exception\Post\PostNotFoundException;
use Acme\Domain\Blog\Model\Post;
use Acme\Domain\Blog\Repository\PostRepository as PostRepositoryInterface;
use Acme\Infrastructure\Bundle\AppBundle\Entity\Post as PostEntity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;

class PostRepository implements PostRepositoryInterface
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
        return new PostEntity();
    }

    /**
     * @inheritdoc
     */
    public function search(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $builder = $this->repository->createQueryBuilder('post');

        if (isset($criteria['category'])) {
            $builder
                ->andWhere('post.category = :category')
                ->setParameter('category', $criteria['category']);
        }

        if (isset($criteria['tag'])) {
            $builder
                ->innerJoin('post.tags', 'tag')
                ->andWhere('tag = :tag')
                ->setParameter('tag', $criteria['tag']);
        } else {
            $builder
                ->leftJoin('post.tags', 'tag')
            ;
        }

        if (isset($criteria['author'])) {
            $builder
                ->andWhere('post.author = :author')
                ->setParameter('author', $criteria['author']);
        }

        if ($orderBy) {
            foreach ($orderBy as $column => $direction) {
                $builder->addOrderBy(sprintf('post.%s', $column), $direction);
            }
        }

        if ($limit && $offset) {
            $builder
                ->setMaxResults($limit)
                ->setFirstResult($offset);
        }

        return $builder->getQuery()->execute();
    }

    /**
     * @inheritDoc
     */
    public function getById($id)
    {
        if (!$post = $this->repository->find($id)) {
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
            $this->manager->persist($post);
            $this->manager->flush($post);
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
            $this->manager->flush($post);
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
            $this->manager->remove($post);
            $this->manager->flush($post);
        } catch (Exception $exception) {
            throw CannotDeletePostException::onException($exception);
        }
    }
}
