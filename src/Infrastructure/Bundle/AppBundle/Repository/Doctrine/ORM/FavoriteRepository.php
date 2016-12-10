<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM;

use Acme\Domain\Blog\Exception\Favorite\CannotAddFavoriteException;
use Acme\Domain\Blog\Exception\Favorite\CannotRemoveFavoriteException;
use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Model\Post;
use Acme\Domain\Blog\Repository\FavoriteRepository as FavoriteRepositoryInterface;
use Acme\Infrastructure\Bundle\AppBundle\Entity\Favorite;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Exception;

class FavoriteRepository implements FavoriteRepositoryInterface
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
    public function listForPost(Post $post)
    {
        $ids = $this->repository->createQueryBuilder('f')
            ->select('f.author')
            ->where('f.post = :post')
            ->setParameter('post', $post)
            ->getQuery()
            ->getScalarResult();

        return $this->manager->getRepository(Author::class)->createQueryBuilder('a')
            ->where('a.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->execute();
    }

    /**
     * @inheritDoc
     */
    public function listForAuthor(Author $author)
    {
        $ids = $this->repository->createQueryBuilder('f')
            ->select('f.post')
            ->where('f.author = :author')
            ->setParameter('author', $author)
            ->getQuery()
            ->getScalarResult();

        return $this->manager->getRepository(Post::class)->createQueryBuilder('p')
            ->where('p.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->execute();
    }

    /**
     * @inheritDoc
     */
    public function add(Post $post, Author $author)
    {
        if ($this->find($post, $author)) {
            throw CannotAddFavoriteException::alreadyFavorited($author, $post);
        }

        $favorite = new Favorite($author, $post, new DateTime());

        try {
            $this->manager->persist($favorite);
            $this->manager->flush($favorite);
        } catch (Exception $exception) {
            throw CannotAddFavoriteException::onException($exception);
        }
    }

    /**
     * @inheritDoc
     */
    public function remove(Post $post, Author $author)
    {
        if (!$favorite = $this->find($post, $author)) {
            throw CannotRemoveFavoriteException::notFavorited($author, $post);
        }

        try {
            $this->manager->remove($favorite);
            $this->manager->flush($favorite);
        } catch (Exception $exception) {
            throw CannotRemoveFavoriteException::onException($exception);
        }
    }

    /**
     * @param Post   $post
     * @param Author $author
     *
     * @return null|Favorite
     */
    private function find(Post $post, Author $author)
    {
        return $this->repository->findOneBy(['author' => $author, 'post' => $post]);
    }
}
