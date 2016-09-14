<?php

namespace Acme\Application\Blog\Command\Comment\Handler;

use Acme\Application\Blog\Command\Comment\DeleteComment;
use Acme\Application\Blog\Event\Comment\CommentDeleted;
use Acme\Domain\Blog\Repository\CommentRepository;
use Acme\Domain\Blog\Repository\PostRepository;
use SimpleBus\Message\Recorder\RecordsMessages;

class DeleteCommentHandler
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
     * @param CommentRepository $repository
     * @param RecordsMessages   $eventRecorder
     */
    public function __construct(CommentRepository $repository, RecordsMessages $eventRecorder)
    {
        $this->repository = $repository;
        $this->eventRecorder = $eventRecorder;
    }

    /**
     * @param DeleteComment $command
     */
    public function __invoke(DeleteComment $command)
    {
        $comment = $this->repository->getById($command->getId());

        $this->repository->delete($comment);

        $this->eventRecorder->record(new CommentDeleted($command->getId()));
    }
}
