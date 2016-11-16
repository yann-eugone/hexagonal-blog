<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Entity\Repository;

use Acme\Domain\Blog\Exception\Comment\CannotCreateCommentException;
use Acme\Domain\Blog\Exception\Comment\CannotDeleteCommentException;
use Acme\Domain\Blog\Exception\Comment\CannotUpdateCommentException;
use Acme\Domain\Blog\Exception\Comment\CommentNotFoundException;
use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Model\Comment;
use Acme\Domain\Blog\Model\Post;
use Acme\Domain\Blog\Repository\CommentCounterRepository;
use Acme\Domain\Blog\Repository\CommentRepository;
use Acme\Infrastructure\Bundle\AppBundle\Entity\CommentEntity;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Exception;

class CommentEntityRepository extends EntityRepository implements CommentRepository, CommentCounterRepository
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
    public function search(Post $post, array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $criteria['post'] = $post;

        return $this->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @inheritDoc
     */
    public function getById($id)
    {
        if (!$comment = $this->find($id)) {
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
    public function count()
    {
        $builder = $this->createQueryBuilder('comment')
            ->select('COUNT(comment)');

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @inheritDoc
     */
    public function countForAuthor(Author $author)
    {
        $builder = $this->createQueryBuilder('comment')
            ->select('COUNT(comment)')
            ->where('comment.author = :author')
            ->setParameter('author', $author);

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @inheritDoc
     */
    public function countThatDay(DateTime $day)
    {
        $builder = $this->createQueryBuilder('comment')
            ->select('COUNT(post)')
            ->where('comment.postedAt = :day')
            ->setParameter('day', $day);

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @inheritDoc
     */
    public function countThatDayForAuthor(DateTime $day, Author $author)
    {
        $builder = $this->createQueryBuilder('comment')
            ->select('COUNT(post)')
            ->where('comment.postedAt = :day')
            ->andWhere('comment.author = :author')
            ->setParameter('day', $day)
            ->setParameter('author', $author);

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @inheritDoc
     */
    public function countBetween(DateTime $from, DateTime $to)
    {
        $builder = $this->createQueryBuilder('comment')
            ->select('COUNT(post)')
            ->where('comment.postedAt >= :from')
            ->andWhere('comment.postedAt <= :to')
            ->setParameter('to', $to)
            ->setParameter('from', $from);

        return intval($builder->getQuery()->getSingleScalarResult());
    }

    /**
     * @inheritDoc
     */
    public function countBetweenForAuthor(DateTime $from, DateTime $to, Author $author)
    {
        $builder = $this->createQueryBuilder('comment')
            ->select('COUNT(post)')
            ->where('comment.postedAt >= :from')
            ->andWhere('comment.postedAt <= :to')
            ->andWhere('comment.author <= :author')
            ->setParameter('to', $to)
            ->setParameter('from', $from)
            ->setParameter('author', $author);

        return intval($builder->getQuery()->getSingleScalarResult());
    }
}
