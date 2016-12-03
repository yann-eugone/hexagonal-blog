<?php

namespace Acme\Application\Common\Command;

interface CommandBus
{
    /**
     * @param object $command
     */
    public function handle($command);
}
