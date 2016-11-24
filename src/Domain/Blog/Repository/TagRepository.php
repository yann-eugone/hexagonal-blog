<?php

namespace Acme\Domain\Blog\Repository;

use Acme\Domain\Blog\Exception\Tag\TagNotFoundException;
use Acme\Domain\Blog\Model\Tag;

interface TagRepository
{
    /**
     * @param int $id
     *
     * @return Tag
     * @throws TagNotFoundException
     */
    public function getById($id);
}
