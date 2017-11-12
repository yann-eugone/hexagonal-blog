<?php

namespace Acme\Tests\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM;

use Acme\Domain\Blog\Model\Category as CategoryInterface;
use Acme\Infrastructure\Bundle\AppBundle\Entity\Category;
use Acme\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM\categoryRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class categoryRepositoryTest extends WebTestCase
{
    /**
     * @var categoryRepository
     */
    private $repository;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->repository = static::$kernel->getContainer()->get('repository.category');
    }

    /**
     * @test
     */
    public function it_list_categories()
    {
        $categories = $this->repository->list();

        $this->assertCount(3, $categories);

        foreach ($categories as $category) {
            $this->assertInstanceOf(Category::class, $category);
            $this->assertInstanceOf(CategoryInterface::class, $category);
        }
    }

    /**
     * @test
     * @expectedException \Acme\Domain\Blog\Exception\Category\CategoryNotFoundException
     */
    public function it_throw_domain_exception_when_category_by_id_not_found()
    {
        $this->repository->getById(9999);
    }

    /**
     * @test
     */
    public function it_get_category_by_id()
    {
        $category = $this->repository->getById(1);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertInstanceOf(CategoryInterface::class, $category);
    }
}
