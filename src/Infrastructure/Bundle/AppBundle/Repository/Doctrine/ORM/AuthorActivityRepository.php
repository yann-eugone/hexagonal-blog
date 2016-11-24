<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Repository\Doctrine\ORM;

use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Repository\AuthorActivityRepository as AuthorActivityRepositoryInterface;
use Acme\Infrastructure\Bundle\AppBundle\Entity\AuthorActivity;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class AuthorActivityRepository implements AuthorActivityRepositoryInterface
{
    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @param EntityManager    $manager
     * @param EntityRepository $repository
     */
    public function __construct(EntityManager $manager, EntityRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function getActivity(Author $author)
    {
        $builder = $this->repository->createQueryBuilder('activity');
        $builder
            ->select('activity', 'author', 'comment')
            ->leftJoin('activity.author', 'author')
            ->leftJoin('activity.comment', 'comment')
            ->where('activity.author', ':author')
            ->setParameter('author', $author)
        ;

        return $builder->getQuery()->execute();
    }

    /**
     * @inheritDoc
     */
    public function add($action, Author $author, DateTime $date, $subject, array $payload)
    {
        $activity = new AuthorActivity($action, $author, $date, $subject, $payload);

        $this->manager->persist($activity);
        $this->manager->flush($activity);
    }
}
