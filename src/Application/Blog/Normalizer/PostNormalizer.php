<?php

namespace Acme\Application\Blog\Normalizer;

use Acme\Domain\Blog\Model\Post;

interface PostNormalizer
{
    /**
     * @param Post $post
     *
     * @return array
     */
    public function normalizeToEvent(Post $post);
}
