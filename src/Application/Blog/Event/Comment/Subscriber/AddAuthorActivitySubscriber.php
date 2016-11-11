<?php

namespace Acme\Application\Blog\Event\Comment\Subscriber;

use Acme\Application\Blog\Event\Comment\CommentCreated;
use Acme\Application\Blog\Event\Comment\CommentUpdated;
use Acme\Application\Blog\Event\Exception\UnexpectedEventException;
use Acme\Domain\Blog\Model\AuthorActivity;
use Acme\Domain\Blog\Repository\AuthorActivityRepository;
use Acme\Domain\Blog\Repository\CommentRepository;
use DateTime;
use LogicException;

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
     * @param CommentCreated|CommentUpdated $event
     */
    public function __invoke($event)
    {
        $actionMap = [
            CommentCreated::class => AuthorActivity::CREATE_COMMENT,
            CommentUpdated::class => AuthorActivity::UPDATE_COMMENT,
        ];

        if (!isset($actionMap[get_class($event)])) {
            throw UnexpectedEventException::create($this, $event);
        }

        $payload = [];
        if ($event instanceof CommentCreated) {
            $payload = $event->getData();
        } elseif ($event instanceof CommentUpdated) {
            $before = $event->getDataBefore();
            $after = $event->getDataAfter();

            $payload = [
                'before' => $before,
                'after' => $after,
            ];
        }

        $comment = $this->commentRepository->getById($event->getId());

        $action = $actionMap[get_class($event)];
        $this->activityRepository->add($action, $comment->getAuthor(), new DateTime(), $comment, $payload);
    }
}
