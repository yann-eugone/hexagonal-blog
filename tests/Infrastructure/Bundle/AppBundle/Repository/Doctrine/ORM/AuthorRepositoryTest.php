<?php

namespace Acme\Tests\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM;

use Acme\Domain\Blog\Model\Author as AuthorInterface;
use Acme\Infrastructure\Bundle\AppBundle\Entity\Author;
use Acme\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthorRepositoryTest extends WebTestCase
{
    /**
     * @var AuthorRepository
     */
    private $repository;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->repository = static::$kernel->getContainer()->get('repository.author');
    }

    /**
     * @test
     * @expectedException \Acme\Domain\Blog\Exception\Author\AuthorNotFoundException
     */
    public function it_throw_domain_exception_when_author_by_id_not_found()
    {
        $this->repository->getById(9999);
    }

    /**
     * @test
     */
    public function it_get_author_by_id()
    {
        $author = $this->repository->getById(1);

        $this->assertInstanceOf(Author::class, $author);
        $this->assertInstanceOf(AuthorInterface::class, $author);
    }

    /**
     * @test
     * @expectedException \Acme\Domain\Blog\Exception\Author\AuthorNotFoundException
     */
    public function it_throw_domain_exception_when_author_by_username_not_found()
    {
        $this->repository->getById('username_that_do_not_exists');
    }

    /**
     * @test
     */
    public function it_get_author_by_username()
    {
        $author = $this->repository->getByUsername('admin');

        $this->assertInstanceOf(Author::class, $author);
        $this->assertInstanceOf(AuthorInterface::class, $author);
    }
}
