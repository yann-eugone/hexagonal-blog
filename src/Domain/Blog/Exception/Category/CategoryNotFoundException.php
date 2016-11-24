<?php

namespace Acme\Domain\Blog\Exception\Category;

use RuntimeException;

class CategoryNotFoundException extends RuntimeException
{
    /**
     * @param int $id
     *
     * @return CategoryNotFoundException
     */
    public static function byId($id)
    {
        return new self(
            sprintf('Category with id %d was not found.', $id)
        );
    }
}
