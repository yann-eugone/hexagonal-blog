<?php

namespace Acme\Application\Blog\Event\Comment\Subscriber;

use Acme\Application\Blog\Event\Comment\CommentCreated;
use Acme\Application\Blog\Event\Comment\CommentEventFactory;
use Acme\Application\Blog\Event\Comment\CommentUpdated;
use Acme\Application\Blog\Normalizer\CommentNormalizer;
use Acme\Domain\Blog\Exception\Comment\CommentNotFoundException;
use Acme\Domain\Blog\Model\AuthorActivity;
use Acme\Domain\Blog\Repository\AuthorActivityRepository;
use Acme\Domain\Blog\Repository\CommentRepository;
use Acme\Tests\Fixtures\Fake\Author;
use Acme\Tests\Fixtures\Fake\Comment;
use Acme\Tests\Fixtures\Fake\Post;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

class AddAuthorActivitySubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AuthorActivityRepository|ObjectProphecy
     */
    private $activityRepository;

    /**
     * @var CommentRepository|ObjectProphecy
     */
    private $commentRepository;

    /**
     * @var CommentNormalizer|ObjectProphecy
     */
    private $normalizer;

    protected function setUp()
    {
        $this->activityRepository = $this->prophesize(AuthorActivityRepository::class);
        $this->commentRepository = $this->prophesize(CommentRepository::class);
        $this->normalizer = $this->prophesize(CommentNormalizer::class);
    }

    protected function tearDown()
    {
        unset(
            $this->activityRepository,
            $this->commentRepository,
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
            $this->commentRepository->reveal()
        );
    }

    /**
     * @param $comment
     *
     * @return CommentCreated
     */
    private function createdEvent($comment)
    {
        return (new CommentEventFactory($this->normalizer->reveal()))->newCreatedEvent($comment);
    }

    /**
     * @param $commentBefore
     * @param $commentAfter
     *
     * @return CommentUpdated
     */
    private function updatedEvent($commentBefore, $commentAfter)
    {
        return (new CommentEventFactory($this->normalizer->reveal()))->newUpdatedEvent($commentBefore, $commentAfter);
    }

    /**
     * @test
     * @expectedException \Acme\Domain\Blog\Exception\Comment\CommentNotFoundException
     */
    public function it_do_not_catch_repository_not_found_exceptions_on_create()
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

        $this->normalizer->normalizeToEvent($comment)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem ipsum by john']);

        $event = $this->createdEvent($comment);

        $this->commentRepository->getById(1)
            ->shouldBeCalledTimes(1)
            ->willThrow(CommentNotFoundException::byId(1));

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
        $post = new Post(1);

        $comment = new Comment(
            1,
            'lorem ipsum',
            new \DateTime('2016-10-11'),
            $author,
            $post
        );

        $this->normalizer->normalizeToEvent($comment)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem ipsum by john']);

        $event = $this->createdEvent($comment);

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

        $subscriber = $this->subscriber();
        $subscriber($event);
    }

    /**
     * @test
     * @expectedException \Acme\Domain\Blog\Exception\Comment\CommentNotFoundException
     */
    public function it_do_not_catch_repository_not_found_exceptions_on_update()
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

        $this->normalizer->normalizeToEvent($commentBefore)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem ipsum by john']);

        $this->normalizer->normalizeToEvent($commentAfter)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem by john']);

        $event = $this->updatedEvent($commentBefore, $commentAfter);

        $this->commentRepository->getById(1)
            ->shouldBeCalledTimes(1)
            ->willThrow(CommentNotFoundException::byId(1));

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

        $this->normalizer->normalizeToEvent($commentBefore)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem ipsum by john']);

        $this->normalizer->normalizeToEvent($commentAfter)
            ->shouldBeCalledTimes(1)
            ->willReturn(['lorem by john']);

        $event = $this->updatedEvent($commentBefore, $commentAfter);

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

        $subscriber = $this->subscriber();
        $subscriber($event);
    }
}
