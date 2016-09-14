<?php

namespace Acme\Infrastructure\Bundle\BlogBundle\Form\Type;

use Acme\Application\Blog\Command\CreatePost;
use Acme\Infrastructure\Bundle\AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreatePostType extends AbstractType implements DataMapperInterface
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
            ->add('author', EntityType::class, ['class' => User::class])
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
                    'data_class' => CreatePost::class,
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
        /** @var CreatePost $data */
        $forms = iterator_to_array($forms);
        /** @var FormInterface[] $forms */

        $forms['title']->setData($data ? $data->getTitle() : null);
        $forms['summary']->setData($data ? $data->getSummary() : null);
        $forms['body']->setData($data ? $data->getBody() : null);
        $forms['author']->setData($data ? $data->getAuthor() : null);
    }

    /**
     * @inheritDoc
     */
    public function mapFormsToData($forms, &$data)
    {
        $forms = iterator_to_array($forms);
        /** @var FormInterface[] $forms */

        $data = new CreatePost(
            $forms['title']->getData(),
            $forms['summary']->getData(),
            $forms['body']->getData(),
            $forms['author']->getData()
        );
    }
}
