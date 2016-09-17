<?php

namespace Acme\Domain\Blog\Model;

interface AuthorActivity
{
    const CREATE_POST = 'post.create';
    const UPDATE_POST = 'post.update';
    const PUBLISH_POST = 'post.publish';
    const DELETE_POST = 'post.delete';

    const CREATE_COMMENT = 'comment.create';
    const UPDATE_COMMENT = 'comment.update';
    const DELETE_COMMENT = 'comment.delete';

    /**
     * @return string
     */
    public function getAction();

    /**
     * @return string
     */
    public function getDate();

    /**
     * @return object
     */
    public function getSubject();
}
