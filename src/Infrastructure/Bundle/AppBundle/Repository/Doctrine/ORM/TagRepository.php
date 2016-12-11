<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM;

use Acme\Domain\Blog\Exception\Tag\TagNotFoundException;
use Acme\Domain\Blog\Repository\TagRepository as TagRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class TagRepository implements TagRepositoryInterface
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
        if (!$tag = $this->repository->find($id)) {
            throw TagNotFoundException::byId($id);
        }

        return $tag;
    }

    /**
     * @inheritDoc
     */
    public function list()
    {
        return $this->repository->findAll();
    }
}
