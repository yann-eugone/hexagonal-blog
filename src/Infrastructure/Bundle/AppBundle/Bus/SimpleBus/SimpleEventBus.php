<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Bus\SimpleBus;

use Acme\Application\Common\Event\EventBus;
use SimpleBus\Message\Bus\MessageBus;

class SimpleEventBus implements EventBus
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
    public function dispatch($event)
    {
        $this->simpleBus->handle($event);
    }
}
