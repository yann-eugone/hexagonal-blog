<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Command;

use Acme\Application\Common\Command\CommandBus;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

abstract class AbstractCommand extends ContainerAwareCommand
{
    /**
     * @return CommandBus
     */
    protected function getCommandBus()
    {
        return $this->getContainer()->get('application_command_bus');
    }
}
