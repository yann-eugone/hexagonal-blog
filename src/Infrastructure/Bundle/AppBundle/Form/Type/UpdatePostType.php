<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Form\Type;

use Acme\Application\Blog\Command\Post\UpdatePost;
use Acme\Infrastructure\Bundle\AppBundle\Entity\CategoryEntity;
use Acme\Infrastructure\Bundle\AppBundle\Entity\TagEntity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdatePostType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('summary', TextType::class)
            ->add('body', TextareaType::class)
            ->add('category', EntityType::class, ['class' => CategoryEntity::class])
            ->add('tags', EntityType::class, ['class' => TagEntity::class, 'multiple' => true])
        ;
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(
                [
                    'data_class' => UpdatePost::class,
                ]
            )
        ;
    }
}
