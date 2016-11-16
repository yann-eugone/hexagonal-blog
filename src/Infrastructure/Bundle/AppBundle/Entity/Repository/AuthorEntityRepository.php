<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Entity\Repository;

use Acme\Domain\Blog\Exception\Author\AuthorNotFoundException;
use Acme\Domain\Blog\Repository\AuthorRepository;
use Doctrine\ORM\EntityRepository;

class AuthorEntityRepository extends EntityRepository implements AuthorRepository
{
    /**
     * @inheritDoc
     */
    public function getById($id)
    {
        if (!$author = $this->find($id)) {
            throw AuthorNotFoundException::byId($id);
        }

        return $author;
    }
}
