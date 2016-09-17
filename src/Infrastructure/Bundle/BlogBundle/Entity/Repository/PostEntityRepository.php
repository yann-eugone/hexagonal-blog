<?php

namespace Acme\Infrastructure\Bundle\BlogBundle\Entity\Repository;

use Acme\Domain\Blog\Exception\Post\CannotCreatePostException;
use Acme\Domain\Blog\Exception\Post\CannotDeletePostException;
use Acme\Domain\Blog\Exception\Post\CannotUpdatePostException;
use Acme\Domain\Blog\Exception\Post\PostNotFoundException;
use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Model\Post;
use Acme\Domain\Blog\Repository\PostCounterRepository;
use Acme\Domain\Blog\Repository\PostRepository as PostRepositoryInterface;
use Acme\Infrastructure\Bundle\BlogBundle\Entity\PostEntity;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Exception;

class PostEntityRepository extends EntityRepository implements PostRepositoryInterface, PostCounterRepository
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

    /**
     * @inheritDoc
     */
    public function incrementCount($incr = 1)
    {
        //nothing to do : not denormalized
    }

    /**
     * @inheritDoc
     */
    public function incrementCountThatDay(DateTime $day, $incr = 1)
    {
        //nothing to do : not denormalized
    }

    /**
     * @inheritDoc
     */
    public function incrementCountForAuthor(Author $author, $incr = 1)
    {
        //nothing to do : not denormalized
    }

    /**
     * @inheritDoc
     */
    public function incrementCountForAuthorThatDay(Author $author, DateTime $day, $incr = 1)
    {
        //nothing to do : not denormalized
    }

    /**
     * @inheritDoc
     */
    public function countThatDay(DateTime $day)
    {
        $builder = $this->createQueryBuilder('post')
            ->select('COUNT(post)')
            ->where('post.postedAt = :day')
            ->setParameter('day', $day);

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @inheritDoc
     */
    public function countThatDayForAuthor(DateTime $day, Author $author)
    {
        $builder = $this->createQueryBuilder('post')
            ->select('COUNT(post)')
            ->where('post.postedAt = :day')
            ->andWhere('post.author = :author')
            ->setParameter('day', $day)
            ->setParameter('author', $author);

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @inheritDoc
     */
    public function countBetween(DateTime $from, DateTime $to)
    {
        $builder = $this->createQueryBuilder('post')
            ->select('COUNT(post)')
            ->where('post.postedAt >= :from')
            ->andWhere('post.postedAt <= :to')
            ->setParameter('to', $to)
            ->setParameter('from', $from);

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @inheritDoc
     */
    public function countBetweenForAuthor(DateTime $from, DateTime $to, Author $author)
    {
        $builder = $this->createQueryBuilder('post')
            ->select('COUNT(post)')
            ->where('post.postedAt >= :from')
            ->andWhere('post.postedAt <= :to')
            ->andWhere('post.author <= :author')
            ->setParameter('to', $to)
            ->setParameter('from', $from)
            ->setParameter('author', $author);

        return intval($builder->getQuery()->getSingleScalarResult());
    }
}
