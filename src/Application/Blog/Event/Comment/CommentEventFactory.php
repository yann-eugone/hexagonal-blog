<?php

namespace Acme\Application\Blog\Event\Comment;

use Acme\Application\Blog\Normalizer\CommentNormalizer;
use Acme\Domain\Blog\Model\Comment;
use DateTime;

class CommentEventFactory
{
    /**
     * @var CommentNormalizer
     */
    private $normalizer;

    /**
     * @param CommentNormalizer $normalizer
     */
    public function __construct(CommentNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @param Comment $comment
     *
     * @return CommentCreated
     */
    public function newCreatedEvent(Comment $comment)
    {
        return new CommentCreated(
            $comment->getId(),
            new DateTime(),
            $this->normalizer->normalizeToEvent($comment)
        );
    }

    /**
     * @param Comment $commentBefore
     * @param Comment $commentAfter
     *
     * @return CommentUpdated
     */
    public function newUpdatedEvent(Comment $commentBefore, Comment $commentAfter)
    {
        return new CommentUpdated(
            $commentAfter->getId(),
            new DateTime(),
            $this->normalizer->normalizeToEvent($commentBefore),
            $this->normalizer->normalizeToEvent($commentAfter)
        );
    }

    /**
     * @param Comment $comment
     *
     * @return CommentDeleted
     */
    public function newDeletedEvent(Comment $comment)
    {
        return new CommentDeleted(
            $comment->getId(),
            new DateTime(),
            $this->normalizer->normalizeToEvent($comment)
        );
    }
}
