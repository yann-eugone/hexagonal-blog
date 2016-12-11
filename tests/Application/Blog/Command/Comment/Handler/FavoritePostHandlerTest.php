<?php

namespace Acme\Tests\Application\Blog\Command\Comment\Handler;

use Acme\Application\Blog\Command\Post\FavoritePost;
use Acme\Application\Blog\Command\Post\Handler\FavoritePostHandler;
use Acme\Application\Blog\Command\Post\PostCommandFactory;
use Acme\Application\Blog\Event\Post\PostEventFactory;
use Acme\Application\Blog\Event\Post\PostFavorited;
use Acme\Application\Blog\Normalizer\PostNormalizer;
use Acme\Application\Common\Event\EventBus;
use Acme\Domain\Blog\Repository\AuthorRepository;
use Acme\Domain\Blog\Repository\FavoriteRepository;
use Acme\Domain\Blog\Repository\PostRepository;
use Acme\Tests\Fixtures\Fake\Author;
use Acme\Tests\Fixtures\Fake\Post;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

class FavoritePostHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PostRepository|ObjectProphecy
     */
    private $postRepository;

    /**
     * @var AuthorRepository|ObjectProphecy
     */
    private $authorRepository;

    /**
     * @var FavoriteRepository|ObjectProphecy
     */
    private $favoriteRepository;

    /**
     * @var EventBus|ObjectProphecy
     */
    private $eventBus;

    protected function setUp()
    {
        $this->postRepository = $this->prophesize(PostRepository::class);
        $this->authorRepository = $this->prophesize(AuthorRepository::class);
        $this->favoriteRepository = $this->prophesize(FavoriteRepository::class);
        $this->eventBus = $this->prophesize(EventBus::class);
    }

    protected function tearDown()
    {
        unset(
            $this->postRepository,
            $this->authorRepository,
            $this->favoriteRepository,
            $this->eventBus
        );
    }

    /**
     * @return FavoritePostHandler
     */
    private function handler()
    {
        return new FavoritePostHandler(
            $this->postRepository->reveal(),
            $this->authorRepository->reveal(),
            $this->favoriteRepository->reveal(),
            new PostEventFactory($this->prophesize(PostNormalizer::class)->reveal()),
            $this->eventBus->reveal()
        );
    }

    /**
     * @param $post
     * @param $author
     *
     * @return FavoritePost
     */
    private function command($post, $author)
    {
        return (new PostCommandFactory())->favoritePost($post, $author);
    }

    /**
     * @test
     */
    public function it_ask_repository_for_creation_and_dispatch_to_event_bus()
    {
        $author = new Author('john');
        $post = new Post(1);
        $command = $this->command($post, $author);

        $this->postRepository->getById(1)
            ->shouldBeCalledTimes(1)
            ->willReturn($post);

        $this->authorRepository->getById('john')
            ->shouldBeCalledTimes(1)
            ->willReturn($author);

        $this->favoriteRepository->add($post, $author)
            ->shouldBeCalledTimes(1);

        $eventAssertions = Argument::allOf(
            Argument::type(PostFavorited::class),
            Argument::which('getPostId', 1),
            Argument::which('getAuthorId', 'john')
        );
        $this->eventBus->dispatch($eventAssertions)
            ->shouldBeCalledTimes(1);

        $handler = $this->handler();
        $handler($command);
    }
}
