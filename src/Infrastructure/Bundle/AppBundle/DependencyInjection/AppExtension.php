<?php

namespace Acme\Infrastructure\Bundle\AppBundle\DependencyInjection;

use Acme\Domain\Blog\Repository\AuthorCommentCounterRepository;
use Acme\Domain\Blog\Repository\CommentCounterRepository;
use Acme\Domain\Blog\Repository\AuthorFavoriteCounterRepository;
use Acme\Domain\Blog\Repository\PostFavoriteCounterRepository;
use Acme\Domain\Blog\Repository\AuthorPostCounterRepository;
use Acme\Domain\Blog\Repository\CategoryPostCounterRepository;
use Acme\Domain\Blog\Repository\PostCounterRepository;
use Acme\Domain\Blog\Repository\TagPostCounterRepository;
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
            AuthorCommentCounterRepository::class => 'repository.counter.comment_author.denormalized',
            CommentCounterRepository::class => 'repository.counter.comment.denormalized',
            AuthorPostCounterRepository::class => 'repository.counter.post_author.denormalized',
            CategoryPostCounterRepository::class => 'repository.counter.post_category.denormalized',
            PostCounterRepository::class => 'repository.counter.post.denormalized',
            TagPostCounterRepository::class => 'repository.counter.post_tag.denormalized',
            AuthorFavoriteCounterRepository::class => 'repository.counter.favorite_author.denormalized',
            PostFavoriteCounterRepository::class => 'repository.counter.favorite_post.denormalized',
        ];
        foreach ($autowiring as $class => $service) {
            $container->findDefinition($service)->setAutowiringTypes([$class]);
        }
    }
}
