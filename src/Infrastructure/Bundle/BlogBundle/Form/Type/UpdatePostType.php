<?php

namespace Acme\Infrastructure\Bundle\BlogBundle\Form\Type;

use Acme\Application\Blog\Command\UpdatePost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdatePostType extends AbstractType implements DataMapperInterface
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class)
            ->add('title', TextType::class)
            ->add('summary', TextType::class)
            ->add('body', TextareaType::class)
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
                    'data_class' => UpdatePost::class,
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
        /** @var UpdatePost $data */
        $forms = iterator_to_array($forms);
        /** @var FormInterface[] $forms */

        $forms['id']->setData($data ? $data->getId() : null);
        $forms['title']->setData($data ? $data->getTitle() : null);
        $forms['summary']->setData($data ? $data->getSummary() : null);
        $forms['body']->setData($data ? $data->getBody() : null);
    }

    /**
     * @inheritDoc
     */
    public function mapFormsToData($forms, &$data)
    {
        $forms = iterator_to_array($forms);
        /** @var FormInterface[] $forms */

        $data = new UpdatePost(
            $forms['id']->getData(),
            $forms['title']->getData(),
            $forms['summary']->getData(),
            $forms['body']->getData()
        );
    }
}
