<?php

namespace Acme\Infrastructure\Bundle\AppBundle\DependencyInjection;

use Acme\Domain\Blog\Repository\CommentAuthorCounterRepository;
use Acme\Domain\Blog\Repository\CommentCounterRepository;
use Acme\Domain\Blog\Repository\PostAuthorCounterRepository;
use Acme\Domain\Blog\Repository\PostCategoryCounterRepository;
use Acme\Domain\Blog\Repository\PostCounterRepository;
use Acme\Domain\Blog\Repository\PostTagCounterRepository;
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
            CommentAuthorCounterRepository::class => 'repository.counter.comment_author.denormalized',
            CommentCounterRepository::class => 'repository.counter.comment.denormalized',
            PostAuthorCounterRepository::class => 'repository.counter.post_author.denormalized',
            PostCategoryCounterRepository::class => 'repository.counter.post_category.denormalized',
            PostCounterRepository::class => 'repository.counter.post.denormalized',
            PostTagCounterRepository::class => 'repository.counter.post_tag.denormalized',
        ];
        foreach ($autowiring as $class => $service) {
            $container->findDefinition($service)->setAutowiringTypes([$class]);
        }
    }
}
