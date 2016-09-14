<?php

namespace Acme\Infrastructure\Bundle\AppBundle;

use Acme\Infrastructure\Bundle\AppBundle\DependencyInjection\AppExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AcmeAppBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function getContainerExtension()
    {
        return new AppExtension();
    }
}
