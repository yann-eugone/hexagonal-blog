<?php

namespace Acme\Application\Blog\Command;

interface CommandBus
{
    /**
     * @param object $command
     */
    public function handle($command);
}
