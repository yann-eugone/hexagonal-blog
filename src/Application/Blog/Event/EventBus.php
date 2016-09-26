<?php

namespace Acme\Application\Blog\Event;

interface EventBus
{
    /**
     * @param object $event
     */
    public function dispatch($event);
}
