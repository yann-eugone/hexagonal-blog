<?php

namespace Acme\Infrastructure\Bundle\AppBundle\DataFixtures\Processor;

use Nelmio\Alice\ProcessorInterface;
use Acme\Infrastructure\Bundle\AppBundle\Entity\AuthorEntity;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthorProcessor implements ProcessorInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @inheritDoc
     */
    public function preProcess($object)
    {
        if (!$object instanceof AuthorEntity) {
            return;
        }

        $object->setPassword($this->encoder->encodePassword($object, $object->getPassword()));
    }

    /**
     * @inheritDoc
     */
    public function postProcess($object)
    {
    }
}
