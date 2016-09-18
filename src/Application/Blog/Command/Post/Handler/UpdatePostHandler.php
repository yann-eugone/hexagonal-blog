<?php

namespace Acme\Application\Blog\Command\Post\Handler;

use Acme\Application\Blog\Command\Post\UpdatePost;
use Acme\Application\Blog\Event\Post\PostUpdated;
use Acme\Domain\Blog\Repository\PostRepository;
use SimpleBus\Message\Recorder\RecordsMessages;

class UpdatePostHandler
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
     * @param UpdatePost $command
     */
    public function __invoke(UpdatePost $command)
    {
        $post = $this->repository->getById($command->getId());

        $post->setTitle($command->getTitle());
        $post->setSummary($command->getSummary());
        $post->setBody($command->getBody());
        $post->setCategory($command->getCategory());
        $post->setTags($command->getTags());

        $this->repository->update($post);

        $this->eventRecorder->record(new PostUpdated($post->getId()));
    }
}
