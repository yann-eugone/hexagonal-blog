<?php

namespace Acme\Application\Blog\Command\Handler;

use Acme\Application\Blog\Command\DeletePost;
use Acme\Application\Blog\Event\PostDeleted;
use Acme\Domain\Blog\Repository\PostRepository;
use SimpleBus\Message\Recorder\RecordsMessages;

class DeletePostHandler
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

        $this->repository->delete($post);

        $this->eventRecorder->record(new PostDeleted($command->getId()));
    }
}
