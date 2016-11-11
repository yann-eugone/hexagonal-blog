<?php

namespace Acme\Infrastructure\Bundle\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\Infrastructure\Bundle\AppBundle\DataFixtures\Processor as Processor;
use Nelmio\Alice\Fixtures;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Loader implements FixtureInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $fixturesDir = $this->container->getParameter('kernel.root_dir') . '/fixtures/minimal';
        $pattern = $fixturesDir . '/%s.yml';

        Fixtures::load(
            [
                sprintf($pattern, 'author'),
                sprintf($pattern, 'tag'),
                sprintf($pattern, 'category'),
            ],
            $manager,
            [
                'providers' => [],
            ],
            [
                new Processor\AuthorProcessor($this->container->get('security.password_encoder')),
            ]
        );
    }
}
