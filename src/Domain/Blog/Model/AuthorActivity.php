<?php

namespace Acme\Domain\Blog\Model;

use DateTime;

interface AuthorActivity
{
    const CREATE_POST = 'post.create';
    const UPDATE_POST = 'post.update';

    const CREATE_COMMENT = 'comment.create';
    const UPDATE_COMMENT = 'comment.update';

    /**
     * @return string
     */
    public function getAction();

    /**
     * @return DateTime
     */
    public function getDate();

    /**
     * @return array
     */
    public function getPayload();

    /**
     * @return object
     */
    public function getSubject();
}
