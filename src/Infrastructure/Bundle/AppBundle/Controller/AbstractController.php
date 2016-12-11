<?php

namespace Acme\Infrastructure\Bundle\AppBundle\Controller;

use Acme\Domain\Blog\Model\Author;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;

abstract class AbstractController extends Controller
{
    /**
     * @return Author|null
     */
    protected function getAuthor()
    {
        $user = $this->getUser();
        if (!$user instanceof Author) {
            return null;
        }

        return $user;
    }

    /**
     * @param FormInterface $form
     *
     * @return bool
     */
    protected function isFormProcessable(FormInterface $form)
    {
        return $form->isSubmitted() && $form->isValid();
    }
}
