<?php

namespace Acme\Application\Blog\Command\Handler;

use Acme\Application\Blog\Command\DeletePost;
use Acme\Application\Blog\Event\PostPublished;
use Acme\Domain\Blog\Repository\PostRepository;
use DateTime;
use SimpleBus\Message\Recorder\RecordsMessages;

class PublishPostHandler
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
     * @param DeletePost $command
     */
    public function __invoke(DeletePost $command)
    {
        $post = $this->repository->getById($command->getId());

        $post->setPublishedAt(new DateTime());

        $this->repository->update($post);

        $this->eventRecorder->record(new PostPublished($command->getId()));
    }
}
