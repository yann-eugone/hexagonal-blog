<?php

namespace Acme\Infrastructure\Bundle\AppBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

class AppExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @inheritDoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
    }

    /**
     * @inheritDoc
     */
    public function prepend(ContainerBuilder $container)
    {
        $config = $container->getExtensionConfig('security');

        $securityConfig = $container->getDefinition('security.firewall_config');
        $securityConfig->setArguments([$config[0]['firewalls']]);
    }
}
