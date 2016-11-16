<?php

namespace Acme\Domain\Blog\Repository;

use Acme\Domain\Blog\Exception\Author\AuthorNotFoundException;
use Acme\Domain\Blog\Model\Author;

interface AuthorRepository
{
    /**
     * @param int $id
     *
     * @return Author
     * @throws AuthorNotFoundException
     */
    public function getById($id);
}
