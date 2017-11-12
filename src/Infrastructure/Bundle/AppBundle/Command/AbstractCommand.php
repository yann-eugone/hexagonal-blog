<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Command;

use Acme\Application\Common\Command\CommandBus;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class AbstractCommand extends ContainerAwareCommand
{
    /**
     * @return CommandBus
     */
    protected function getCommandBus()
    {
        return $this->getContainer()->get('application_command_bus');
    }

    /**
     * @param mixed $attributes
     * @param mixed $object
     *
     * @return bool
     */
    public function isGranted($attributes, $object = null)
    {
        return $this->getContainer()->get('security.authorization_checker')->isGranted($attributes, $object);
    }

    /**
     * @param UserInterface $user
     * @param string        $firewall
     */
    protected function authenticate(UserInterface $user, $firewall = 'main')
    {
        $this->getContainer()->get('security.token_storage')->setToken(
            new UsernamePasswordToken($user, null, $firewall, $user->getRoles())
        );
    }
}
