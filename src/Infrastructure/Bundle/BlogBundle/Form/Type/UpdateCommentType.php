<?php

namespace Acme\Infrastructure\Bundle\BlogBundle\Form\Type;

use Acme\Application\Blog\Command\Comment\CreateComment;
use Acme\Application\Blog\Command\Comment\UpdateComment;
use Acme\Infrastructure\Bundle\AppBundle\Entity\User;
use Acme\Infrastructure\Bundle\BlogBundle\Entity\PostEntity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateCommentType extends AbstractType implements DataMapperInterface
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class)
            ->add('text', TextType::class)
            ->add('author', EntityType::class, ['class' => User::class])
            ->add('post', EntityType::class, ['class' => PostEntity::class])
            ->setDataMapper($this)
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
                    'data_class' => UpdateComment::class,
                    'empty_data' => null,
                ]
            )
        ;
    }

    /**
     * @inheritDoc
     */
    public function mapDataToForms($data, $forms)
    {
        /** @var UpdateComment $data */
        $forms = iterator_to_array($forms);
        /** @var FormInterface[] $forms */

        $forms['id']->setData($data ? $data->getId() : null);
        $forms['text']->setData($data ? $data->getText() : null);
    }

    /**
     * @inheritDoc
     */
    public function mapFormsToData($forms, &$data)
    {
        $forms = iterator_to_array($forms);
        /** @var FormInterface[] $forms */

        $data = new UpdateComment(
            $forms['id']->getData(),
            $forms['text']->getData()
        );
    }
}
