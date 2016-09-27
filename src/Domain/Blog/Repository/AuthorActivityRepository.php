<?php

namespace Acme\Domain\Blog\Repository;

use Acme\Domain\Blog\Model\AuthorActivity;
use Acme\Domain\Blog\Model\Author;
use DateTime;

interface AuthorActivityRepository
{
    /**
     * @param Author $author
     *
     * @return AuthorActivity[]
     */
    public function getActivity(Author $author);

    /**
     * @param string   $action
     * @param Author   $author
     * @param DateTime $date
     * @param object   $subject
     * @param array    $payload
     */
    public function add($action, Author $author, DateTime $date, $subject, array $payload);
}
