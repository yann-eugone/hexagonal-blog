<?php

namespace Acme\Tests\Application\Blog\Command\Post\Handler;

use Acme\Application\Blog\Command\Post\UpdatePost;
use Acme\Application\Blog\Command\Post\Handler\UpdatePostHandler;
use Acme\Application\Blog\Command\Post\PostCommandFactory;
use Acme\Application\Common\Event\EventBus;
use Acme\Application\Blog\Event\Post\PostUpdated;
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

class UpdatePostHandlerTest extends \PHPUnit_Framework_TestCase
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
     * @return UpdatePostHandler
     */
    private function handler()
    {
        return new UpdatePostHandler(
            $this->repository->reveal(),
            new PostEventFactory($this->normalizer->reveal()),
            $this->eventBus->reveal()
        );
    }

    /**
     * @param $post
     *
     * @return UpdatePost
     */
    private function command($post)
    {
        return (new PostCommandFactory())->newUpdateCommand($post);
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
        $originalCategory = new Category('lorem');
        $category = new Category('ipsum');
        $tag1 = new Tag('foo');
        $tag2 = new Tag('bar');
        $tag3 = new Tag('baz');

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
        $command->setTitle('ipsum');
        $command->setBody('lorem ipsum dolor sit amet, consectetur adipiscing elit');
        $command->setCategory($category);
        $command->setTags([$tag1, $tag3]);

        $this->repository->getById(1)
            ->shouldBeCalledTimes(1)
            ->willReturn($post);

        $this->repository->update($post)
            ->shouldBeCalledTimes(1);

        $postBeforeAssertions = Argument::allOf(
            Argument::type(Post::class),
            Argument::which('getTitle', 'lorem ipsum')
        );
        $this->normalizer->normalizeToEvent($postBeforeAssertions)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem ipsum by john']);
        $postAfterAssertions = Argument::allOf(
            Argument::type(Post::class),
            Argument::which('getTitle', 'ipsum')
        );
        $this->normalizer->normalizeToEvent($postAfterAssertions)
            ->shouldBeCalledTimes(1)
            ->willReturn(['ipsum by john']);

        $eventAssertions = Argument::allOf(
            Argument::type(PostUpdated::class),
            Argument::which('getId', 1),
            Argument::which('getDataBefore', ['lorem ipsum by john']),
            Argument::which('getDataAfter', ['ipsum by john'])
        );
        $this->eventBus->dispatch($eventAssertions)
            ->shouldBeCalledTimes(1);

        $handler = $this->handler();
        $handler($command);

        static::assertSame($author, $post->author);
        static::assertSame($category, $post->category);
        static::assertSame([$tag1, $tag3], $post->tags);
        static::assertSame('ipsum', $post->title);
        static::assertSame('lorem ipsum dolor sit amet, consectetur adipiscing elit', $post->body);
    }
}
