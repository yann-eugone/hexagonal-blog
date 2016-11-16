<?php

namespace Acme\Tests\Application\Blog\Command\Comment\Handler;

use Acme\Application\Blog\Command\Comment\UpdateComment;
use Acme\Application\Blog\Command\Comment\Handler\UpdateCommentHandler;
use Acme\Application\Blog\Command\Comment\CommentCommandFactory;
use Acme\Application\Blog\Event\EventBus;
use Acme\Application\Blog\Event\Comment\CommentUpdated;
use Acme\Application\Blog\Event\Comment\CommentEventFactory;
use Acme\Application\Blog\Normalizer\CommentNormalizer;
use Acme\Domain\Blog\Exception\Comment\CommentNotFoundException;
use Acme\Domain\Blog\Repository\CommentRepository;
use Acme\Tests\Fixtures\Fake\Author;
use Acme\Tests\Fixtures\Fake\Category;
use Acme\Tests\Fixtures\Fake\Comment;
use Acme\Tests\Fixtures\Fake\Post;
use Acme\Tests\Fixtures\Fake\Tag;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

class UpdateCommentHandlerTest extends \PHPUnit_Framework_TestCase
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
     * @return UpdateCommentHandler
     */
    private function handler()
    {
        return new UpdateCommentHandler(
            $this->repository->reveal(),
            new CommentEventFactory($this->normalizer->reveal()),
            $this->eventBus->reveal()
        );
    }

    /**
     * @param $comment
     *
     * @return UpdateComment
     */
    private function command($comment)
    {
        return (new CommentCommandFactory())->newUpdateCommand($comment);
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
        $this->repository->update(Argument::any())
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
        $command->setText('ipsum');

        $this->repository->getById(1)
            ->shouldBeCalledTimes(1)
            ->willReturn($comment);

        $this->repository->update($comment)
            ->shouldBeCalledTimes(1);

        $commentBeforeAssertions = Argument::allOf(
            Argument::type(Comment::class),
            Argument::which('getComment', 'lorem ipsum')
        );
        $this->normalizer->normalizeToEvent($commentBeforeAssertions)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem ipsum by john']);
        $commentAfterAssertions = Argument::allOf(
            Argument::type(Comment::class),
            Argument::which('getComment', 'ipsum')
        );
        $this->normalizer->normalizeToEvent($commentAfterAssertions)
            ->shouldBeCalledTimes(1)
            ->willReturn(['ipsum by john']);

        $eventAssertions = Argument::allOf(
            Argument::type(CommentUpdated::class),
            Argument::which('getId', 1),
            Argument::which('getDataBefore', ['lorem ipsum by john']),
            Argument::which('getDataAfter', ['ipsum by john'])
        );
        $this->eventBus->dispatch($eventAssertions)
            ->shouldBeCalledTimes(1);

        $handler = $this->handler();
        $handler($command);

        static::assertSame($author, $comment->author);
        static::assertSame('ipsum', $comment->comment);
    }
}
