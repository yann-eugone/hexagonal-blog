<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM;

use Acme\Domain\Blog\Exception\Author\AuthorNotFoundException;
use Acme\Domain\Blog\Repository\AuthorRepository as AuthorRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class AuthorRepository implements AuthorRepositoryInterface
{
    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @param EntityRepository $repository
     */
    public function __construct(EntityRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function getById($id)
    {
        if (!$author = $this->repository->find($id)) {
            throw AuthorNotFoundException::byId($id);
        }

        return $author;
    }

    /**
     * @inheritDoc
     */
    public function getByUsername($username)
    {
        if (!$author = $this->repository->findOneBy(['username' => $username])) {
            throw AuthorNotFoundException::byUsername($username);
        }

        return $author;
    }
}
