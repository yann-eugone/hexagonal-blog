<?php

namespace Acme\Domain\Blog\Repository;

use Acme\Domain\Blog\Exception\Post\CannotCreatePostException;
use Acme\Domain\Blog\Exception\Post\CannotDeletePostException;
use Acme\Domain\Blog\Exception\Post\CannotUpdatePostException;
use Acme\Domain\Blog\Exception\Post\PostNotFoundException;
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
    public function search(array $criteria, array $orderBy = null, $limit = null, $offset = null);

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
