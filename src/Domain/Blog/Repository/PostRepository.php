<?php

namespace Acme\Domain\Blog\Repository;

use Acme\Domain\Blog\Exception\CannotCreatePostException;
use Acme\Domain\Blog\Exception\CannotDeletePostException;
use Acme\Domain\Blog\Exception\CannotUpdatePostException;
use Acme\Domain\Blog\Exception\PostNotFoundException;
use Acme\Domain\Blog\Model\Post;

interface PostRepository
{
    /**
     * @return Post
     */
    public function instance();

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param null  $limit
     * @param null  $offset
     *
     * @return Post[]
     */
    public function list(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    /**
     * @param int $id
     *
     * @return Post
     * @throws PostNotFoundException
     */
    public function getById($id);

    /**
     * @param Post $post
     *
     * @throws CannotCreatePostException
     */
    public function create(Post $post);

    /**
     * @param Post $post
     *
     * @throws CannotUpdatePostException
     */
    public function update(Post $post);

    /**
     * @param Post $post
     *
     * @throws CannotDeletePostException
     */
    public function delete(Post $post);
}
