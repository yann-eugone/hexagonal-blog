<?php

namespace Acme\Application\Blog\Command\Comment\Handler;

use Acme\Application\Blog\Command\Comment\CreateComment;
use Acme\Application\Blog\Event\Comment\CommentCreated;
use Acme\Domain\Blog\Repository\CommentRepository;
use DateTime;
use SimpleBus\Message\Recorder\RecordsMessages;

class CreateCommentHandler
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
     * @param CreateComment $command
     */
    public function __invoke(CreateComment $command)
    {
        $comment = $this->repository->instance();

        $command->setComment($comment);

        $comment->setComment($command->getText());
        $comment->setPost($command->getPost());
        $comment->setAuthor($command->getAuthor());
        $comment->setPostedAt(new DateTime());

        $this->repository->create($comment);

        $this->eventRecorder->record(new CommentCreated($comment->getId()));
    }
}
