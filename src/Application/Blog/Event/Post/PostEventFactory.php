<?php

namespace Acme\Application\Blog\Event\Post;

use Acme\Application\Blog\Normalizer\PostNormalizer;
use Acme\Domain\Blog\Model\Post;
use DateTime;

class PostEventFactory
{
    /**
     * @var PostNormalizer
     */
    private $normalizer;

    /**
     * @param PostNormalizer $normalizer
     */
    public function __construct(PostNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @param Post $post
     *
     * @return PostCreated
     */
    public function newCreatedEvent(Post $post)
    {
        return new PostCreated(
            $post->getId(),
            new DateTime(),
            $this->normalizer->normalizeToEvent($post)
        );
    }

    /**
     * @param Post $postBefore
     * @param Post $postAfter
     *
     * @return PostUpdated
     */
    public function newUpdatedEvent(Post $postBefore, Post $postAfter)
    {
        return new PostUpdated(
            $postAfter->getId(),
            new DateTime(),
            $this->normalizer->normalizeToEvent($postBefore),
            $this->normalizer->normalizeToEvent($postAfter)
        );
    }

    /**
     * @param Post $post
     *
     * @return PostDeleted
     */
    public function newDeletedEvent(Post $post)
    {
        return new PostDeleted(
            $post->getId(),
            new DateTime()
        );
    }
}
