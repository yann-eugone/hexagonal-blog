<?php

namespace Acme\Tests\Application\Blog\Event\Author\Subscriber;

use Acme\Application\Blog\Event\Author\Subscriber\RecordActivitySubscriber;
use Acme\Application\Blog\Event\Comment\CommentCreated;
use Acme\Application\Blog\Event\Comment\CommentEventFactory;
use Acme\Application\Blog\Event\Comment\CommentUpdated;
use Acme\Application\Blog\Event\Post\PostCreated;
use Acme\Application\Blog\Event\Post\PostEventFactory;
use Acme\Application\Blog\Event\Post\PostUpdated;
use Acme\Application\Blog\Normalizer\CommentNormalizer;
use Acme\Application\Blog\Normalizer\PostNormalizer;
use Acme\Domain\Blog\Exception\Comment\CommentNotFoundException;
use Acme\Domain\Blog\Exception\Post\PostNotFoundException;
use Acme\Domain\Blog\Model\AuthorActivity;
use Acme\Domain\Blog\Repository\AuthorActivityRepository;
use Acme\Domain\Blog\Repository\CommentRepository;
use Acme\Domain\Blog\Repository\PostRepository;
use Acme\Tests\Fixtures\Fake\Author;
use Acme\Tests\Fixtures\Fake\Category;
use Acme\Tests\Fixtures\Fake\Comment;
use Acme\Tests\Fixtures\Fake\Post;
use Acme\Tests\Fixtures\Fake\Tag;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

class RecordActivitySubscriberTest extends \PHPUnit_Framework_TestCase
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
     * @var CommentRepository|ObjectProphecy
     */
    private $commentRepository;

    /**
     * @var PostNormalizer|ObjectProphecy
     */
    private $postNormalizer;

    /**
     * @var CommentNormalizer|ObjectProphecy
     */
    private $commentNormalizer;

    protected function setUp()
    {
        $this->activityRepository = $this->prophesize(AuthorActivityRepository::class);
        $this->postRepository = $this->prophesize(PostRepository::class);
        $this->commentRepository = $this->prophesize(CommentRepository::class);
        $this->postNormalizer = $this->prophesize(PostNormalizer::class);
        $this->commentNormalizer = $this->prophesize(CommentNormalizer::class);
    }

    protected function tearDown()
    {
        unset(
            $this->activityRepository,
            $this->postRepository,
            $this->commentRepository,
            $this->postNormalizer,
            $this->commentNormalizer
        );
    }

    /**
     * @return RecordActivitySubscriber
     */
    private function subscriber()
    {
        return new RecordActivitySubscriber(
            $this->activityRepository->reveal(),
            $this->postRepository->reveal(),
            $this->commentRepository->reveal()
        );
    }

    /**
     * @param $post
     *
     * @return PostCreated
     */
    private function postCreatedEvent($post)
    {
        return (new PostEventFactory($this->postNormalizer->reveal()))->newCreatedEvent($post);
    }

    /**
     * @param $postBefore
     * @param $postAfter
     *
     * @return PostUpdated
     */
    private function postUpdatedEvent($postBefore, $postAfter)
    {
        return (new PostEventFactory($this->postNormalizer->reveal()))->newUpdatedEvent($postBefore, $postAfter);
    }

    /**
     * @param $comment
     *
     * @return CommentCreated
     */
    private function commentCreatedEvent($comment)
    {
        return (new CommentEventFactory($this->commentNormalizer->reveal()))->newCreatedEvent($comment);
    }

    /**
     * @param $commentBefore
     * @param $commentAfter
     *
     * @return CommentUpdated
     */
    private function commentUpdatedEvent($commentBefore, $commentAfter)
    {
        return (new CommentEventFactory($this->commentNormalizer->reveal()))->newUpdatedEvent($commentBefore, $commentAfter);
    }

    /**
     * @test
     * @expectedException \Acme\Domain\Blog\Exception\Post\PostNotFoundException
     */
    public function it_do_not_catch_repository_not_found_exceptions_on_post_create()
    {
        $post = new Post(
            1,
            'lorem ipsum',
            'lorem ipsum dolor'
        );

        $this->postNormalizer->normalizeToEvent($post)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem ipsum by john']);

        $event = $this->postCreatedEvent($post);

        $this->postRepository->getById(1)
            ->shouldBeCalledTimes(1)
            ->willThrow(PostNotFoundException::byId(1));

        $this->activityRepository->add(Argument::cetera())
            ->shouldNotBeCalled();

        $this->subscriber()->postCreated($event);
    }

    /**
     * @test
     */
    public function it_add_activity_to_repository_on_post_create()
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

        $this->postNormalizer->normalizeToEvent($post)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem ipsum by john']);

        $event = $this->postCreatedEvent($post);

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

        $this->subscriber()->postCreated($event);
    }

    /**
     * @test
     * @expectedException \Acme\Domain\Blog\Exception\Post\PostNotFoundException
     */
    public function it_do_not_catch_repository_not_found_exceptions_on_post_update()
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

        $this->postNormalizer->normalizeToEvent($postBefore)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem ipsum by john']);

        $this->postNormalizer->normalizeToEvent($postAfter)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem by john']);

        $event = $this->postUpdatedEvent($postBefore, $postAfter);

        $this->postRepository->getById(1)
            ->shouldBeCalledTimes(1)
            ->willThrow(PostNotFoundException::byId(1));

        $this->activityRepository->add(Argument::cetera())
            ->shouldNotBeCalled();

        $this->subscriber()->postUpdated($event);
    }

    /**
     * @test
     */
    public function it_add_activity_to_repository_on_post_update()
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

        $this->postNormalizer->normalizeToEvent($postBefore)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem ipsum by john']);

        $this->postNormalizer->normalizeToEvent($postAfter)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem by john']);

        $event = $this->postUpdatedEvent($postBefore, $postAfter);

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

        $this->subscriber()->postUpdated($event);
    }

    /**
     * @test
     * @expectedException \Acme\Domain\Blog\Exception\Comment\CommentNotFoundException
     */
    public function it_do_not_catch_repository_not_found_exceptions_on_comment_create()
    {
        $author = new Author('john');
        $post = new Post(1);

        $comment = new Comment(
            1,
            'lorem ipsum',
            new \DateTime('2016-10-11'),
            $author,
            $post
        );

        $this->commentNormalizer->normalizeToEvent($comment)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem ipsum by john']);

        $event = $this->commentCreatedEvent($comment);

        $this->commentRepository->getById(1)
            ->shouldBeCalledTimes(1)
            ->willThrow(CommentNotFoundException::byId(1));

        $this->activityRepository->add(Argument::cetera())
            ->shouldNotBeCalled();

        $this->subscriber()->commentCreated($event);
    }

    /**
     * @test
     */
    public function it_add_activity_to_repository_on_comment_create()
    {
        $author = new Author('john');
        $post = new Post(1);

        $comment = new Comment(
            1,
            'lorem ipsum',
            new \DateTime('2016-10-11'),
            $author,
            $post
        );

        $this->commentNormalizer->normalizeToEvent($comment)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem ipsum by john']);

        $event = $this->commentCreatedEvent($comment);

        $this->commentRepository->getById(1)
            ->shouldBeCalledTimes(1)
            ->willReturn($comment);

        $this->activityRepository->add(
            AuthorActivity::CREATE_COMMENT,
            $author,
            Argument::type(\DateTime::class),
            $comment,
            ['lorem ipsum by john']
        )->shouldBeCalledTimes(1);

        $this->subscriber()->commentCreated($event);
    }

    /**
     * @test
     * @expectedException \Acme\Domain\Blog\Exception\Comment\CommentNotFoundException
     */
    public function it_do_not_catch_repository_not_found_exceptions_on_comment_update()
    {
        $author = new Author('john');
        $post = new Post(1);

        $commentBefore = new Comment(
            1,
            'lorem ipsum',
            new \DateTime('2016-10-11'),
            $author,
            $post
        );
        $commentAfter = new Comment(
            1,
            'lorem',
            new \DateTime('2016-10-11'),
            $author,
            $post
        );

        $this->commentNormalizer->normalizeToEvent($commentBefore)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem ipsum by john']);

        $this->commentNormalizer->normalizeToEvent($commentAfter)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem by john']);

        $event = $this->commentUpdatedEvent($commentBefore, $commentAfter);

        $this->commentRepository->getById(1)
            ->shouldBeCalledTimes(1)
            ->willThrow(CommentNotFoundException::byId(1));

        $this->activityRepository->add(Argument::cetera())
            ->shouldNotBeCalled();

        $this->subscriber()->commentUpdated($event);
    }

    /**
     * @test
     */
    public function it_add_activity_to_repository_on_comment_update()
    {
        $author = new Author('john');
        $post = new Post(1);

        $commentBefore = new Comment(
            1,
            'lorem ipsum',
            new \DateTime('2016-10-11'),
            $author,
            $post
        );
        $commentAfter = new Comment(
            1,
            'lorem',
            new \DateTime('2016-10-11'),
            $author,
            $post
        );

        $this->commentNormalizer->normalizeToEvent($commentBefore)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem ipsum by john']);

        $this->commentNormalizer->normalizeToEvent($commentAfter)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem by john']);

        $event = $this->commentUpdatedEvent($commentBefore, $commentAfter);

        $this->commentRepository->getById(1)
            ->shouldBeCalledTimes(1)
            ->willReturn($commentAfter);

        $this->activityRepository->add(
            AuthorActivity::UPDATE_COMMENT,
            $author,
            Argument::type(\DateTime::class),
            $commentAfter,
            [
                'before' => ['lorem ipsum by john'],
                'after' => ['lorem by john'],
            ]
        )->shouldBeCalledTimes(1);

        $this->subscriber()->commentUpdated($event);
    }
}
