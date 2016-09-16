<?php

namespace Acme\Application\Blog\Event\Comment\Subscriber;

use Acme\Application\Blog\Event\Comment\CommentCreated;
use Acme\Application\Blog\Event\Comment\CommentDeleted;
use Acme\Application\Blog\Event\Comment\CommentUpdated;
use Acme\Domain\Blog\Model\Activity\AuthorActivity;
use Acme\Domain\Blog\Repository\Activity\AuthorActivityRepository;
use Acme\Domain\Blog\Repository\CommentRepository;
use DateTime;

class AddAuthorActivitySubscriber
{
    /**
     * @var AuthorActivityRepository
     */
    private $activityRepository;

    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * @param AuthorActivityRepository $activityRepository
     * @param CommentRepository        $commentRepository
     */
    public function __construct(AuthorActivityRepository $activityRepository, CommentRepository $commentRepository)
    {
        $this->activityRepository = $activityRepository;
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param CommentCreated|CommentUpdated|CommentDeleted $event
     */
    public function __invoke($event)
    {
        $actionMap = [
            CommentCreated::class => AuthorActivity::CREATE_COMMENT,
            CommentUpdated::class => AuthorActivity::UPDATE_COMMENT,
            CommentDeleted::class => AuthorActivity::DELETE_COMMENT,
        ];

        if (!isset($actionMap[get_class($event)])) {
            return; //todo at this point we should probably report something
        }

        $comment = $this->commentRepository->getById($event->getId());

        $action = $actionMap[get_class($event)];
        $this->activityRepository->add($action, $comment->getAuthor(), new DateTime(), $comment);
    }
}
