<?php

namespace Acme\Tests\Application\Blog\Command\Comment\Handler;

use Acme\Application\Blog\Command\Comment\CreateComment;
use Acme\Application\Blog\Command\Comment\Handler\CreateCommentHandler;
use Acme\Application\Blog\Command\Comment\CommentCommandFactory;
use Acme\Application\Blog\Event\EventBus;
use Acme\Application\Blog\Event\Comment\CommentCreated;
use Acme\Application\Blog\Event\Comment\CommentEventFactory;
use Acme\Application\Blog\Normalizer\CommentNormalizer;
use Acme\Domain\Blog\Repository\CommentRepository;
use Acme\Tests\Fixtures\Fake\Author;
use Acme\Tests\Fixtures\Fake\Comment;
use Acme\Tests\Fixtures\Fake\Post;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

class CreateCommentHandlerTest extends \PHPUnit_Framework_TestCase
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
     * @return CreateCommentHandler
     */
    private function handler()
    {
        return new CreateCommentHandler(
            $this->repository->reveal(),
            new CommentEventFactory($this->normalizer->reveal()),
            $this->eventBus->reveal()
        );
    }

    /**
     * @param $author
     * @param $post
     *
     * @return CreateComment
     */
    private function command($author, $post)
    {
        return (new CommentCommandFactory())->newCreateCommand($author, $post);
    }

    /**
     * @test
     */
    public function it_ask_repository_for_creation_and_dispatch_to_event_bus()
    {
        $author = new Author('john');
        $post = new Post(1);
        $command = $this->command($author, $post);
        $command->setText('lorem ipsum');

        $comment = new Comment(1);

        $this->repository->instance()
            ->shouldBeCalledTimes(1)
            ->willReturn($comment);

        $this->repository->create($comment)
            ->shouldBeCalledTimes(1);

        $this->normalizer->normalizeToEvent($comment)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem ipsum by john']);

        $eventAssertions = Argument::allOf(
            Argument::type(CommentCreated::class),
            Argument::which('getId', 1),
            Argument::which('getData', ['lorem ipsum by john'])
        );
        $this->eventBus->dispatch($eventAssertions)
            ->shouldBeCalledTimes(1);

        $handler = $this->handler();
        $handler($command);

        static::assertSame($comment, $command->getComment());
        static::assertSame($author, $comment->author);
        static::assertSame('lorem ipsum', $comment->comment);
        static::assertInstanceOf(\DateTime::class, $comment->postedAt);
    }
}
