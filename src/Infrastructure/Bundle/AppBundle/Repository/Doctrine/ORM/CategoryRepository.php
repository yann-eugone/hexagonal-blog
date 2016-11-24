<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM;

use Acme\Domain\Blog\Exception\Category\CategoryNotFoundException;
use Acme\Domain\Blog\Repository\CategoryRepository as CategoryRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class CategoryRepository implements CategoryRepositoryInterface
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
        if (!$category = $this->repository->find($id)) {
            throw CategoryNotFoundException::byId($id);
        }

        return $category;
    }
}
