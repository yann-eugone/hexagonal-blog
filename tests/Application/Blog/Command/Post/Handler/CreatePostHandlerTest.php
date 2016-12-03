<?php

namespace Acme\Tests\Application\Blog\Command\Post\Handler;

use Acme\Application\Blog\Command\Post\CreatePost;
use Acme\Application\Blog\Command\Post\Handler\CreatePostHandler;
use Acme\Application\Blog\Command\Post\PostCommandFactory;
use Acme\Application\Common\Event\EventBus;
use Acme\Application\Blog\Event\Post\PostCreated;
use Acme\Application\Blog\Event\Post\PostEventFactory;
use Acme\Application\Blog\Normalizer\PostNormalizer;
use Acme\Domain\Blog\Repository\PostRepository;
use Acme\Tests\Fixtures\Fake\Author;
use Acme\Tests\Fixtures\Fake\Category;
use Acme\Tests\Fixtures\Fake\Post;
use Acme\Tests\Fixtures\Fake\Tag;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

class CreatePostHandlerTest extends \PHPUnit_Framework_TestCase
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
     * @return CreatePostHandler
     */
    private function handler()
    {
        return new CreatePostHandler(
            $this->repository->reveal(),
            new PostEventFactory($this->normalizer->reveal()),
            $this->eventBus->reveal()
        );
    }

    /**
     * @param $author
     *
     * @return CreatePost
     */
    private function command($author)
    {
        return (new PostCommandFactory())->newCreateCommand($author);
    }

    /**
     * @test
     */
    public function it_ask_repository_for_creation_and_dispatch_to_event_bus()
    {
        $author = new Author('john');
        $category = new Category('lorem');
        $tag1 = new Tag('foo');
        $tag2 = new Tag('bar');
        $command = $this->command($author);
        $command->setTitle('lorem ipsum');
        $command->setBody('lorem ipsum dolor sit amet');
        $command->setCategory($category);
        $command->setTags([$tag1, $tag2]);

        $post = new Post(1);

        $this->repository->instance()
            ->shouldBeCalledTimes(1)
            ->willReturn($post);

        $this->repository->create($post)
            ->shouldBeCalledTimes(1);

        $this->normalizer->normalizeToEvent($post)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem ipsum by john']);

        $eventAssertions = Argument::allOf(
            Argument::type(PostCreated::class),
            Argument::which('getId', 1),
            Argument::which('getData', ['lorem ipsum by john'])
        );
        $this->eventBus->dispatch($eventAssertions)
            ->shouldBeCalledTimes(1);

        $handler = $this->handler();
        $handler($command);

        static::assertSame($post, $command->getPost());
        static::assertSame($author, $post->author);
        static::assertSame($category, $post->category);
        static::assertSame([$tag1, $tag2], $post->tags);
        static::assertSame('lorem ipsum', $post->title);
        static::assertSame('lorem ipsum dolor sit amet', $post->body);
        static::assertInstanceOf(\DateTime::class, $post->postedAt);
    }
}
