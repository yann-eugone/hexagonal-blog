<?php

namespace Acme\Domain\Blog\Repository\Activity;

use Acme\Domain\Blog\Model\Activity\AuthorActivity;
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
     */
    public function add($action, Author $author, DateTime $date, $subject);
}
