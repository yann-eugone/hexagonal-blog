<?php

namespace Acme\Application\Blog\Command\Handler;

use Acme\Application\Blog\Command\CreatePost;
use Acme\Application\Blog\Event\PostCreated;
use Acme\Domain\Blog\Repository\PostRepository;
use DateTime;
use SimpleBus\Message\Recorder\RecordsMessages;

class CreatePostHandler
{
    /**
     * @var PostRepository
     */
    private $repository;

    /**
     * @var RecordsMessages
     */
    private $eventRecorder;

    /**
     * @param PostRepository  $repository
     * @param RecordsMessages $eventRecorder
     */
    public function __construct(PostRepository $repository, RecordsMessages $eventRecorder)
    {
        $this->repository = $repository;
        $this->eventRecorder = $eventRecorder;
    }

    /**
     * @param CreatePost $command
     */
    public function __invoke(CreatePost $command)
    {
        $post = $this->repository->instance();

        $command->setPost($post);

        $post->setTitle($command->getTitle());
        $post->setSummary($command->getSummary());
        $post->setBody($command->getBody());
        $post->setAuthor($command->getAuthor());
        $post->setPostedAt(new DateTime());

        $this->repository->create($post);

        $this->eventRecorder->record(new PostCreated($post->getId()));
    }
}
