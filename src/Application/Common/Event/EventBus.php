<?php

namespace Acme\Application\Common\Event;

interface EventBus
{
    /**
     * @param object $event
     */
    public function dispatch($event);
}
