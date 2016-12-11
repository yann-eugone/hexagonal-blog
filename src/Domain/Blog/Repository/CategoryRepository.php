<?php

namespace Acme\Domain\Blog\Repository;

use Acme\Domain\Blog\Exception\Category\CategoryNotFoundException;
use Acme\Domain\Blog\Model\Category;

interface CategoryRepository
{
    /**
     * @param int $id
     *
     * @return Category
     * @throws CategoryNotFoundException
     */
    public function getById($id);

    /**
     * @return Category[]
     */
    public function list();
}
