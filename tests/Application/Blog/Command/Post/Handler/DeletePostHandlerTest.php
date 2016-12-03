<?php

namespace Acme\Tests\Application\Blog\Command\Post\Handler;

use Acme\Application\Blog\Command\Post\DeletePost;
use Acme\Application\Blog\Command\Post\Handler\DeletePostHandler;
use Acme\Application\Blog\Command\Post\PostCommandFactory;
use Acme\Application\Common\Event\EventBus;
use Acme\Application\Blog\Event\Post\PostDeleted;
use Acme\Application\Blog\Event\Post\PostEventFactory;
use Acme\Application\Blog\Normalizer\PostNormalizer;
use Acme\Domain\Blog\Exception\Post\PostNotFoundException;
use Acme\Domain\Blog\Repository\PostRepository;
use Acme\Tests\Fixtures\Fake\Author;
use Acme\Tests\Fixtures\Fake\Category;
use Acme\Tests\Fixtures\Fake\Post;
use Acme\Tests\Fixtures\Fake\Tag;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

class DeletePostHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PostRepository|ObjectProphecy
     */
    private $repository;

    /**
     * @var \Acme\Application\Common\Event\EventBus|ObjectProphecy
     */
    private $eventBus;

    /**
     * @var PostNormalizer|ObjectProphecy
     */
    private $normalizer;

    protected function setUp()
    {
        $this->repository = $this->prophesize(PostRepository::class);
        $this->eventBus = $this->prophesize(EventBus::class);
        $this->normalizer = $this->prophesize(PostNormalizer::class);
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
     * @return DeletePostHandler
     */
    private function handler()
    {
        return new DeletePostHandler(
            $this->repository->reveal(),
            new PostEventFactory($this->normalizer->reveal()),
            $this->eventBus->reveal()
        );
    }

    /**
     * @param $post
     *
     * @return DeletePost
     */
    private function command($post)
    {
        return (new PostCommandFactory())->newDeleteCommand($post);
    }

    /**
     * @test
     * @expectedException \Acme\Domain\Blog\Exception\Post\PostNotFoundException
     */
    public function it_do_not_catch_repository_not_found_exceptions()
    {
        $author = new Author('john');
        $originalCategory = new Category('lorem');
        $tag1 = new Tag('foo');
        $tag2 = new Tag('bar');

        $post = new Post(
            1,
            'lorem ipsum',
            'lorem ipsum dolor',
            'lorem ipsum dolor sit amet',
            new \DateTime('2016-09-27'),
            $author,
            $originalCategory,
            [$tag1, $tag2]
        );

        $command = $this->command($post);

        $this->repository->getById(1)
            ->shouldBeCalledTimes(1)
            ->willThrow(PostNotFoundException::byId(1));
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
        $category = new Category('ipsum');
        $tag1 = new Tag('foo');
        $tag2 = new Tag('bar');

        $post = new Post(
            1,
            'lorem ipsum',
            'lorem ipsum dolor',
            'lorem ipsum dolor sit amet',
            new \DateTime('2016-09-27'),
            $author,
            $category,
            [$tag1, $tag2]
        );

        $command = $this->command($post);

        $this->repository->getById(1)
            ->shouldBeCalledTimes(1)
            ->willReturn($post);

        $this->repository->delete($post)
            ->shouldBeCalledTimes(1);

        $eventAssertions = Argument::allOf(
            Argument::type(PostDeleted::class),
            Argument::which('getId', 1)
        );
        $this->eventBus->dispatch($eventAssertions)
            ->shouldBeCalledTimes(1);

        $handler = $this->handler();
        $handler($command);
    }
}
