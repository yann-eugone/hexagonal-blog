<?php

namespace Acme\Application\Blog\Event\Author\Subscriber;

use Acme\Application\Blog\Event\Comment\CommentCreated;
use Acme\Application\Blog\Event\Comment\CommentUpdated;
use Acme\Application\Blog\Event\Post\PostCreated;
use Acme\Application\Blog\Event\Post\PostUpdated;
use Acme\Domain\Blog\Model\AuthorActivity;
use Acme\Domain\Blog\Repository\AuthorActivityRepository;
use Acme\Domain\Blog\Repository\CommentRepository;
use Acme\Domain\Blog\Repository\PostRepository;
use DateTime;

class RecordActivitySubscriber
{
    /**
     * @var AuthorActivityRepository
     */
    private $activityRepository;

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * @param AuthorActivityRepository $activityRepository
     * @param PostRepository           $postRepository
     * @param CommentRepository        $commentRepository
     */
    public function __construct(
        AuthorActivityRepository $activityRepository,
        PostRepository $postRepository,
        CommentRepository $commentRepository
    ) {
        $this->activityRepository = $activityRepository;
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param PostCreated $event
     */
    public function postCreated(PostCreated $event)
    {
        $post = $this->postRepository->getById($event->getId());

        $this->activityRepository->add(
            AuthorActivity::CREATE_POST,
            $post->getAuthor(),
            new DateTime(),
            $post,
            $event->getData()
        );
    }

    /**
     * @param PostUpdated $event
     */
    public function postUpdated(PostUpdated $event)
    {
        $post = $this->postRepository->getById($event->getId());

        $this->activityRepository->add(
            AuthorActivity::UPDATE_POST,
            $post->getAuthor(),
            new DateTime(),
            $post,
            [
                'before' => $event->getDataBefore(),
                'after' => $event->getDataAfter(),
            ]
        );
    }

    /**
     * @param CommentCreated $event
     */
    public function commentCreated(CommentCreated $event)
    {
        $comment = $this->commentRepository->getById($event->getId());

        $this->activityRepository->add(
            AuthorActivity::CREATE_COMMENT,
            $comment->getAuthor(),
            new DateTime(),
            $comment,
            $event->getData()
        );
    }

    /**
     * @param CommentUpdated $event
     */
    public function commentUpdated(CommentUpdated $event)
    {
        $comment = $this->commentRepository->getById($event->getId());

        $this->activityRepository->add(
            AuthorActivity::UPDATE_COMMENT,
            $comment->getAuthor(),
            new DateTime(),
            $comment,
            [
                'before' => $event->getDataBefore(),
                'after' => $event->getDataAfter(),
            ]
        );
    }
}
