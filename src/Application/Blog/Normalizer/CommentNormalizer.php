<?php

namespace Acme\Application\Blog\Normalizer;

use Acme\Domain\Blog\Model\Comment;

interface CommentNormalizer
{
    /**
     * @param Comment $comment
     *
     * @return array
     */
    public function normalizeToEvent(Comment $comment);
}
