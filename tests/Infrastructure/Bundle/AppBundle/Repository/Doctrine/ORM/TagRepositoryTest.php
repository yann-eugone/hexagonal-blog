<?php

namespace Acme\Tests\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM;

use Acme\Domain\Blog\Model\Tag as TagInterface;
use Acme\Infrastructure\Bundle\AppBundle\Entity\Tag;
use Acme\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM\TagRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TagRepositoryTest extends WebTestCase
{
    /**
     * @var TagRepository
     */
    private $repository;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->repository = static::$kernel->getContainer()->get('repository.tag');
    }

    /**
     * @test
     */
    public function it_list_tags()
    {
        $tags = $this->repository->list();

        $this->assertCount(4, $tags);

        foreach ($tags as $tag) {
            $this->assertInstanceOf(Tag::class, $tag);
            $this->assertInstanceOf(TagInterface::class, $tag);
        }
    }

    /**
     * @test
     * @expectedException \Acme\Domain\Blog\Exception\Tag\TagNotFoundException
     */
    public function it_throw_domain_exception_when_tag_by_id_not_found()
    {
        $this->repository->getById(9999);
    }

    /**
     * @test
     */
    public function it_get_tag_by_id()
    {
        $tag = $this->repository->getById(1);

        $this->assertInstanceOf(Tag::class, $tag);
        $this->assertInstanceOf(TagInterface::class, $tag);
    }
}
