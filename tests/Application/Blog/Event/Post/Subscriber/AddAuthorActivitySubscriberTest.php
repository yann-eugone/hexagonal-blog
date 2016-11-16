<?php

namespace Acme\Application\Blog\Event\Post\Subscriber;

use Acme\Application\Blog\Event\Post\PostCreated;
use Acme\Application\Blog\Event\Post\PostEventFactory;
use Acme\Application\Blog\Event\Post\PostUpdated;
use Acme\Application\Blog\Normalizer\PostNormalizer;
use Acme\Domain\Blog\Exception\Post\PostNotFoundException;
use Acme\Domain\Blog\Model\AuthorActivity;
use Acme\Domain\Blog\Repository\AuthorActivityRepository;
use Acme\Domain\Blog\Repository\PostRepository;
use Acme\Tests\Fixtures\Fake\Author;
use Acme\Tests\Fixtures\Fake\Category;
use Acme\Tests\Fixtures\Fake\Post;
use Acme\Tests\Fixtures\Fake\Tag;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

class AddAuthorActivitySubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AuthorActivityRepository|ObjectProphecy
     */
    private $activityRepository;

    /**
     * @var PostRepository|ObjectProphecy
     */
    private $postRepository;

    /**
     * @var PostNormalizer|ObjectProphecy
     */
    private $normalizer;

    protected function setUp()
    {
        $this->activityRepository = $this->prophesize(AuthorActivityRepository::class);
        $this->postRepository = $this->prophesize(PostRepository::class);
        $this->normalizer = $this->prophesize(PostNormalizer::class);
    }

    protected function tearDown()
    {
        unset(
            $this->activityRepository,
            $this->postRepository,
            $this->normalizer
        );
    }

    /**
     * @return AddAuthorActivitySubscriber
     */
    private function subscriber()
    {
        return new AddAuthorActivitySubscriber(
            $this->activityRepository->reveal(),
            $this->postRepository->reveal()
        );
    }

    /**
     * @param $post
     *
     * @return PostCreated
     */
    private function createdEvent($post)
    {
        return (new PostEventFactory($this->normalizer->reveal()))->newCreatedEvent($post);
    }

    /**
     * @param $postBefore
     * @param $postAfter
     *
     * @return PostUpdated
     */
    private function updatedEvent($postBefore, $postAfter)
    {
        return (new PostEventFactory($this->normalizer->reveal()))->newUpdatedEvent($postBefore, $postAfter);
    }

    /**
     * @test
     * @expectedException \Acme\Domain\Blog\Exception\Post\PostNotFoundException
     */
    public function it_do_not_catch_repository_not_found_exceptions_on_create()
    {
        $post = new Post(
            1,
            'lorem ipsum',
            'lorem ipsum dolor'
        );

        $this->normalizer->normalizeToEvent($post)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem ipsum by john']);

        $event = $this->createdEvent($post);

        $this->postRepository->getById(1)
            ->shouldBeCalledTimes(1)
            ->willThrow(PostNotFoundException::byId(1));

        $this->activityRepository->add(Argument::cetera())
            ->shouldNotBeCalled();

        $subscriber = $this->subscriber();
        $subscriber($event);
    }

    /**
     * @test
     */
    public function it_add_activity_to_repository_on_create()
    {
        $author = new Author('john');
        $category = new Category('lorem');
        $tag1 = new Tag('foo');
        $tag2 = new Tag('bar');
        $post = new Post(
            1,
            'lorem ipsum',
            'lorem ipsum',
            'lorem ipsum dolor',
            new \DateTime('2016-10-11'),
            $author,
            $category,
            [$tag1, $tag2]
        );

        $this->normalizer->normalizeToEvent($post)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem ipsum by john']);

        $event = $this->createdEvent($post);

        $this->postRepository->getById(1)
            ->shouldBeCalledTimes(1)
            ->willReturn($post);

        $this->activityRepository->add(
            AuthorActivity::CREATE_POST,
            $author,
            Argument::type(\DateTime::class),
            $post,
            ['lorem ipsum by john']
        )->shouldBeCalledTimes(1);

        $subscriber = $this->subscriber();
        $subscriber($event);
    }

    /**
     * @test
     * @expectedException \Acme\Domain\Blog\Exception\Post\PostNotFoundException
     */
    public function it_do_not_catch_repository_not_found_exceptions_on_update()
    {
        $postBefore = new Post(
            1,
            'lorem ipsum',
            'lorem ipsum dolor'
        );
        $postAfter = new Post(
            1,
            'lorem',
            'lorem ipsum'
        );

        $this->normalizer->normalizeToEvent($postBefore)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem ipsum by john']);

        $this->normalizer->normalizeToEvent($postAfter)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem by john']);

        $event = $this->updatedEvent($postBefore, $postAfter);

        $this->postRepository->getById(1)
            ->shouldBeCalledTimes(1)
            ->willThrow(PostNotFoundException::byId(1));

        $this->activityRepository->add(Argument::cetera())
            ->shouldNotBeCalled();

        $subscriber = $this->subscriber();
        $subscriber($event);
    }

    /**
     * @test
     */
    public function it_add_activity_to_repository_on_update()
    {
        $author = new Author('john');
        $category1 = new Category('lorem');
        $category2 = new Category('ipsum');
        $tag1 = new Tag('foo');
        $tag2 = new Tag('bar');
        $tag3 = new Tag('baz');
        $postBefore = new Post(
            1,
            'lorem ipsum',
            'lorem ipsum',
            'lorem ipsum dolor',
            new \DateTime('2016-10-11'),
            $author,
            $category1,
            [$tag1, $tag2]
        );
        $postAfter = new Post(
            1,
            'lorem',
            'lorem',
            'lorem ipsum',
            new \DateTime('2016-10-11'),
            $author,
            $category2,
            [$tag1, $tag3]
        );

        $this->normalizer->normalizeToEvent($postBefore)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem ipsum by john']);

        $this->normalizer->normalizeToEvent($postAfter)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem by john']);

        $event = $this->updatedEvent($postBefore, $postAfter);

        $this->postRepository->getById(1)
            ->shouldBeCalledTimes(1)
            ->willReturn($postAfter);

        $this->activityRepository->add(
            AuthorActivity::UPDATE_POST,
            $author,
            Argument::type(\DateTime::class),
            $postAfter,
            [
                'before' => ['lorem ipsum by john'],
                'after' => ['lorem by john'],
            ]
        )->shouldBeCalledTimes(1);

        $subscriber = $this->subscriber();
        $subscriber($event);
    }
}
