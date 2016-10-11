<?php

namespace Acme\Tests\Application\Blog\Command\Comment\Handler;

use Acme\Application\Blog\Command\Comment\DeleteComment;
use Acme\Application\Blog\Command\Comment\Handler\DeleteCommentHandler;
use Acme\Application\Blog\Command\Comment\CommentCommandFactory;
use Acme\Application\Blog\Event\EventBus;
use Acme\Application\Blog\Event\Comment\CommentDeleted;
use Acme\Application\Blog\Event\Comment\CommentEventFactory;
use Acme\Application\Blog\Normalizer\CommentNormalizer;
use Acme\Domain\Blog\Exception\Comment\CommentNotFoundException;
use Acme\Domain\Blog\Repository\CommentRepository;
use Acme\Tests\DataFixtures\Author;
use Acme\Tests\DataFixtures\Category;
use Acme\Tests\DataFixtures\Comment;
use Acme\Tests\DataFixtures\Post;
use Acme\Tests\DataFixtures\Tag;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

class DeleteCommentHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CommentRepository|ObjectProphecy
     */
    private $repository;

    /**
     * @var EventBus|ObjectProphecy
     */
    private $eventBus;

    /**
     * @var CommentNormalizer|ObjectProphecy
     */
    private $normalizer;

    protected function setUp()
    {
        $this->repository = $this->prophesize(CommentRepository::class);
        $this->eventBus = $this->prophesize(EventBus::class);
        $this->normalizer = $this->prophesize(CommentNormalizer::class);
    }

    protected function tearDown()
    {
        unset(
            $this->repository,
            $this->eventBus,
            $this->normalizer
        );
    }

    /**
     * @return DeleteCommentHandler
     */
    private function handler()
    {
        return new DeleteCommentHandler(
            $this->repository->reveal(),
            new CommentEventFactory($this->normalizer->reveal()),
            $this->eventBus->reveal()
        );
    }

    /**
     * @param $comment
     *
     * @return DeleteComment
     */
    private function command($comment)
    {
        return (new CommentCommandFactory())->newDeleteCommand($comment);
    }

    /**
     * @test
     * @expectedException \Acme\Domain\Blog\Exception\Comment\CommentNotFoundException
     */
    public function it_do_not_catch_repository_not_found_exceptions()
    {
        $author = new Author('john');
        $post = new Post(1);

        $comment = new Comment(
            1,
            'lorem ipsum',
            new \DateTime('2016-09-27'),
            $author,
            $post
        );

        $command = $this->command($comment);

        $this->repository->getById(1)
            ->shouldBeCalledTimes(1)
            ->willThrow(CommentNotFoundException::byId(1));
        $this->repository->delete(Argument::any())
            ->shouldNotBeCalled();

        $this->eventBus->dispatch(Argument::any())
            ->shouldNotBeCalled();

        $handler = $this->handler();
        $handler($command);
    }

    /**
     * @test
     */
    public function it_ask_repository_for_creation_and_dispatch_to_event_bus()
    {
        $author = new Author('john');
        $post = new Post(1);

        $comment = new Comment(
            1,
            'lorem ipsum',
            new \DateTime('2016-09-27'),
            $author,
            $post
        );

        $command = $this->command($comment);

        $this->repository->getById(1)
            ->shouldBeCalledTimes(1)
            ->willReturn($comment);

        $this->repository->delete($comment)
            ->shouldBeCalledTimes(1);

        $eventAssertions = Argument::allOf(
            Argument::type(CommentDeleted::class),
            Argument::which('getId', 1)
        );
        $this->eventBus->dispatch($eventAssertions)
            ->shouldBeCalledTimes(1);

        $handler = $this->handler();
        $handler($command);
    }
}
