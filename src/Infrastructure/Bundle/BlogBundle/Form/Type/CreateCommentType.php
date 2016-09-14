<?php

namespace Acme\Infrastructure\Bundle\BlogBundle\Form\Type;

use Acme\Application\Blog\Command\Comment\CreateComment;
use Acme\Infrastructure\Bundle\AppBundle\Entity\User;
use Acme\Infrastructure\Bundle\BlogBundle\Entity\PostEntity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateCommentType extends AbstractType implements DataMapperInterface
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
                    'data_class' => CreateComment::class,
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
        /** @var CreateComment $data */
        $forms = iterator_to_array($forms);
        /** @var FormInterface[] $forms */

        $forms['text']->setData($data ? $data->getText() : null);
        $forms['author']->setData($data ? $data->getAuthor() : null);
        $forms['post']->setData($data ? $data->getPost() : null);
    }

    /**
     * @inheritDoc
     */
    public function mapFormsToData($forms, &$data)
    {
        $forms = iterator_to_array($forms);
        /** @var FormInterface[] $forms */

        $data = new CreateComment(
            $forms['text']->getData(),
            $forms['author']->getData(),
            $forms['post']->getData()
        );
    }
}
