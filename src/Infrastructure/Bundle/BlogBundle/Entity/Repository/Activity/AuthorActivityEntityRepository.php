<?php

namespace Acme\Infrastructure\Bundle\BlogBundle\Entity\Repository\Activity;

use Acme\Domain\Blog\Model\Author;
use Acme\Domain\Blog\Repository\Activity\AuthorActivityRepository;
use Acme\Infrastructure\Bundle\BlogBundle\Entity\Activity\AuthorActivityEntity;
use DateTime;
use Doctrine\ORM\EntityRepository;

class AuthorActivityEntityRepository extends EntityRepository implements AuthorActivityRepository
{
    /**
     * @inheritDoc
     */
    public function getActivity(Author $author)
    {
        $builder = $this->createQueryBuilder('activity');
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
    public function add($action, Author $author, DateTime $date, $subject)
    {
        $activity = new AuthorActivityEntity($action, $author, $date, $subject);

        $this->getEntityManager()->persist($activity);
        $this->getEntityManager()->flush($activity);
    }
}
