<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Bus\SimpleBus;

use Acme\Application\Common\Command\CommandBus;
use SimpleBus\Message\Bus\MessageBus;

class SimpleCommandBus implements CommandBus
{
    /**
     * @var MessageBus
     */
    private $simpleBus;

    /**
     * @param MessageBus $simpleBus
     */
    public function __construct(MessageBus $simpleBus)
    {
        $this->simpleBus = $simpleBus;
    }

    /**
     * @inheritDoc
     */
    public function handle($command)
    {
        $this->simpleBus->handle($command);
    }
}
