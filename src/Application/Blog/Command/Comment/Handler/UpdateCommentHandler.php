<?php

namespace Acme\Application\Blog\Command\Comment\Handler;

use Acme\Application\Blog\Command\Comment\UpdateComment;
use Acme\Application\Blog\Event\Comment\CommentUpdated;
use Acme\Domain\Blog\Repository\CommentRepository;
use SimpleBus\Message\Recorder\RecordsMessages;

class UpdateCommentHandler
{
    /**
     * @var CommentRepository
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
     * @param UpdateComment $command
     */
    public function __invoke(UpdateComment $command)
    {
        $comment = $this->repository->getById($command->getId());

        $comment->setComment($command->getText());

        $this->repository->update($comment);

        $this->eventRecorder->record(new CommentUpdated($comment->getId()));
    }
}
