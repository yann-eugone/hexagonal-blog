<?php

namespace Acme\Domain\Blog\Model;

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
     * @return string
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
