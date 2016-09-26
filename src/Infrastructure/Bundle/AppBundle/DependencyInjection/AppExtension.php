<?php

namespace Acme\Infrastructure\Bundle\AppBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AppExtension extends Extension implements PrependExtensionInterface, CompilerPassInterface
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

    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        $autowiring = [
            NormalizerInterface::class => 'serializer',
        ];
        foreach ($autowiring as $class => $service) {
            $container->findDefinition($service)->setAutowiringTypes([$class]);
        }
    }
}
