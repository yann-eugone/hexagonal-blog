<?php

namespace Acme\Application\Blog\Event\Post\Subscriber;

use Acme\Application\Blog\Event\Exception\UnexpectedEventException;
use Acme\Application\Blog\Event\Post\PostCreated;
use Acme\Application\Blog\Event\Post\PostUpdated;
use Acme\Domain\Blog\Model\AuthorActivity;
use Acme\Domain\Blog\Repository\AuthorActivityRepository;
use Acme\Domain\Blog\Repository\PostRepository;
use DateTime;

class AddAuthorActivitySubscriber
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
     * @param AuthorActivityRepository $activityRepository
     * @param PostRepository           $PostRepository
     */
    public function __construct(AuthorActivityRepository $activityRepository, PostRepository $PostRepository)
    {
        $this->activityRepository = $activityRepository;
        $this->postRepository = $PostRepository;
    }

    /**
     * @param PostCreated|PostUpdated $event
     */
    public function __invoke($event)
    {
        $actionMap = [
            PostCreated::class => AuthorActivity::CREATE_POST,
            PostUpdated::class => AuthorActivity::UPDATE_POST,
        ];

        if (!isset($actionMap[get_class($event)])) {
            throw UnexpectedEventException::create($this, $event);
        }

        $payload = [];
        if ($event instanceof PostCreated) {
            $payload = $event->getData();
        } elseif ($event instanceof PostUpdated) {
            $before = $event->getDataBefore();
            $after = $event->getDataAfter();

            $payload = [
                'before' => $before,
                'after' => $after,
            ];
        }

        $post = $this->postRepository->getById($event->getId());

        $action = $actionMap[get_class($event)];
        $this->activityRepository->add($action, $post->getAuthor(), new DateTime(), $post, $payload);
    }
}
