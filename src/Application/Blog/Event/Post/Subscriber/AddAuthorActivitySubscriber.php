<?php

namespace Acme\Application\Blog\Event\Post\Subscriber;

use Acme\Application\Blog\Event\Post\PostCreated;
use Acme\Application\Blog\Event\Post\PostDeleted;
use Acme\Application\Blog\Event\Post\PostPublished;
use Acme\Application\Blog\Event\Post\PostUpdated;
use Acme\Domain\Blog\Model\Activity\AuthorActivity;
use Acme\Domain\Blog\Repository\Activity\AuthorActivityRepository;
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
     * @param PostCreated|PostUpdated|PostPublished|PostDeleted $event
     */
    public function __invoke($event)
    {
        $actionMap = [
            PostCreated::class => AuthorActivity::CREATE_POST,
            PostUpdated::class => AuthorActivity::UPDATE_POST,
            PostPublished::class => AuthorActivity::PUBLISH_POST,
            PostDeleted::class => AuthorActivity::DELETE_POST,
        ];

        if (!isset($actionMap[get_class($event)])) {
            return; //todo at this point we should probably report something
        }

        $post = $this->postRepository->getById($event->getId());

        $action = $actionMap[get_class($event)];
        $this->activityRepository->add($action, $post->getAuthor(), new DateTime(), $post);
    }
}
